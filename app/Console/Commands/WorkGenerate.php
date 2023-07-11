<?php

namespace App\Console\Commands;

use App\Domains\Metric\Helpers\MetricRabbitHelper;
use App\Services\RabbitMQService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class WorkGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:generate {quantity} {delay}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate data for RabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quantity = (int)$this->argument('quantity');

        $delay = (int)$this->argument('delay');

        $connection = (new RabbitMQService())->getConnection();

        $channel = $connection->channel();

        $channel->queue_declare(MetricRabbitHelper::QUEUE_NAME, false, true, false, false);

        $channel->exchange_declare(MetricRabbitHelper::EXCHANGE_NAME, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind(MetricRabbitHelper::QUEUE_NAME, MetricRabbitHelper::EXCHANGE_NAME);

        $this->startSeeder($channel, $quantity,  $delay);

        $channel->close();
        $connection->close();
    }

    protected function generateJson(): array
    {
        return [
            'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
            'device' => rand(1, 100),
            'data' => [
                'sensor' => rand(1, 3),
                'temp' => rand(2, 20)
            ]
        ];
    }

    protected function startSeeder(AMQPChannel $channel, int $quantity, int $delay)
    {
        for ($i = 1; $i <= $quantity; $i++){
            $messageBody = json_encode($this->generateJson());
            $message = new AMQPMessage(
                $messageBody,
                array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
            );
            $channel->basic_publish($message, MetricRabbitHelper::EXCHANGE_NAME);

            usleep($delay * 1000);
        }
    }

}
