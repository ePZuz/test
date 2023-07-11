<template>
  <div>
    <input type="date" @change="updateFilters" v-model="startAt">
    <input type="date" @change="updateFilters" v-model="endAt">
    <select @change="updateFilters" v-model="sensor">
      <option value="0">Не выбрано</option>
      <option v-for="sensor in sensors" :value="sensor.id">Сенсор №{{sensor.id}}</option>
    </select>
  </div>
  <div>
    <table>
      <tr v-for="row in rows">
        <td>
          Сенсор №{{row.sensor}}
        </td>
        <td>
          {{row.avg_temp}}
        </td>
      </tr>
    </table>
  </div>
</template>
<style>
  table{
    border-collapse: collapse;
  }

  table td {
    border: 1px solid #000;
    padding: 5px;
  }
</style>
<script>
  export default {
    data(){
      return {
        startAt: null,
        endAt: null,
        sensor: "0",
        // переделать, получить через кликхаус
        sensors: [
          {id: 1},
          {id: 2},
          {id: 3},
        ],
        rows: [],
      }
    },
    mounted() {
      this.getRows();
    },
    methods:{
      updateFilters(){
        this.getRows()
      },
      getRows(){
        axios.post('/api/metrics/get', {
          startAt: this.startAt,
          endAt: this.endAt,
          sensorId: this.sensor
        })
          .then((result) => {
            this.rows = result.data;
          })
          .catch((error) => {
            alert(error);
          })
      },
    },

  }
</script>