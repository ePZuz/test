<?php

namespace App\Console\Commands;

use App\Domains\Metric\Helpers\MetricRabbitHelper;
use App\Domains\Metric\Models\Metric;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class WorkConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = (new RabbitMQService())->getConnection();

        $channel = $connection->channel();

        $channel->queue_declare(MetricRabbitHelper::QUEUE_NAME, false, true, false, false);

        $channel->exchange_declare(MetricRabbitHelper::EXCHANGE_NAME, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind(MetricRabbitHelper::QUEUE_NAME, MetricRabbitHelper::EXCHANGE_NAME);

        $channel->basic_consume(MetricRabbitHelper::QUEUE_NAME, 'consumer', false, false, false, false, fn($message) => $this->handleProcess($message));

        $channel->consume();
    }


    protected function handleProcess(AMQPMessage $message)
    {
        $json = json_decode($message->getBody(), true);
        Metric::create([
            'device' => $json['device'],
            'datetime' => $json['datetime'],
            'sensor' => $json['data']['sensor'],
            'temperature' => $json['data']['temp'],
        ]);
    }


}
