<template>
    <div class="controls d-flex align-items-center justify-content-between mb-2">
        <label
            for="datetime"
            class="col-form-label text-center"
        >{{ duration }}</label>

        <div class="btn-group btn-group">
            <button
            type="button"
            class="btn btn-outline-primary px-3 py-1 py-md-0"
            :disabled="week === 0"
            @click="week--">&laquo;</button>
            <button
            type="button"
            class="btn btn-outline-primary px-3 py-1 py-md-0"
            :disabled="week > 5"
            @click="week++">&raquo;</button>
        </div>
    </div>

    <div class="position-relative w-100 wrapper">
        <div class="fixed-wrapper">
            <table class="fixed-header">
                <thead>
                    <tr>
                    <th class="time">{{ time }}</th>
                    <th
                    v-for="(day, i) in days"
                    :key="day">
                    {{ day + ' ' + getTimeString(i) }}</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="scrollable">
            <table>
                <thead>
                    <tr>
                    <th class="time">{{ time }}</th>
                    <th
                    v-for="(day, i) in days"
                    :key="day">
                    {{ day + ' ' + getTimeString(i) }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="time in timeIntervals" :key="time">
                        <td>{{ time }}</td>
                        <td
                        class="cell"
                        v-for="(day, i) in days"
                        :key="`${day}-${time}`"
                        @click="active = isTimePassed(time, i) ? active : toValidTimeFormat(i, time)"
                        :class="{
                            danger: isTimePassed(time, i),
                            active: toValidTimeFormat(i, time) === active
                        }"
                    ></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input type="text" name="timezone" id="timezone" value="UTC" hidden>
    <input type="text" :value="active" name="datetime" hidden>
</template>

  <script>
  export default {
    data() {
      return {
        active: '',
        week: 0,
        now: new Date(),
        days: window.lang.dates?.split(';'),
        time: window?.lang?.time,
        duration: window?.lang?.duration,
        timeIntervals: [],
        minTimeToReuest: 10
      };
    },

    created() {
      this.generateTimeIntervals();
    },

    methods: {
      generateTimeIntervals() {
        const start = 8.5 * 60
        const end = 18 * 60
        const interval = 10
        const result = []

        for (let i = start; i < end; i += interval) {
          const hours = Math.floor(i / 60)
          const minutes = i % 60
          result.push(`${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`)
        }
        this.timeIntervals = result
      },

      isTimePassed(time, day) {
        const t = time.split(':')

        const now = new Date()
        const then = this.getDateWithWeekDay(day)
        const givenTime = new Date(
            then.getFullYear(),
            then.getMonth(),
            then.getDate(),
            +t[0],
            +t[1] - this.minTimeToReuest
        )

        return  now > givenTime
      },

      getTimeString(i) {
        const now = this.getDateWithWeekDay(i)

        return now.getDate().toString().padStart(2, '0') +
        '.' + (now.getMonth() + 1).toString().padStart(2, '0')
      },

      toValidTimeFormat(i, time) {
        const [hours, minutes] = time.split(':').map(Number)

        const date = this.getDateWithWeekDay(i)

        date.setHours(hours, minutes, 0, 0)
        return date.toISOString().slice(0, -8)
      },

      getDateWithWeekDay(weekDay) {
        const date = new Date()
        const currentDay = date.getDay() === 0 ? 7 : date.getDay()
        const day = (weekDay + 1) - currentDay
        date.setDate(date.getDate() + day + (this.week * 7))
        return date
      }
    }
  };
  </script>

<style scoped>

* {
scrollbar-width: thin;
scrollbar-color: #8a8a8a #ffffff;
}

*::-webkit-scrollbar {
width: 12px;
}

*::-webkit-scrollbar-track {
background: transparent;
}

*::-webkit-scrollbar-thumb {
background-color: #8a8a8a;
border-radius: 5px;
border: 3px solid #ffffff;
}

table {
    border-collapse: collapse;
    width: 100%;
}

table thead {
    opacity: 0;
}

td, th {
    border: 1px solid darkgray;
    text-align: center;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

.controls {
    width: 90%;
    min-width: 264px;
    margin: 0 auto;
}

.scrollable {
    position: relative;
    width: 90%;
    min-width: 264px;
    padding: 0;
    margin: 0 auto;
    overflow: auto;
    overflow-x: hidden;
    max-height: 40vh;
}

.wrapper {
    overflow-x: auto;
}

.fixed-header {
    position: absolute;
    transition: none;
    top: 0;
    width: calc(100% - 11px);
    background-color: white;
    box-shadow: inset 0px -1px 0px #eee,
                0px 5px 8px -5px rgba(0,0,0,.1);
}

.fixed-wrapper {
    width: 90%;
    min-width: 264px;
    padding: 0;
    margin: 0 auto;
    position: relative;
    z-index: 5;
}

.fixed-header thead {
    opacity: 1;
    box-shadow: inset 0px -1px 0px #eee,
                0px 5px 8px -5px rgba(0,0,0,.1);
}

.time {
    width: 60px;
}

.cell:hover {
    background-color: lightgray;
    cursor: pointer;
}

.danger {
    background-color: #f5a4a4;
}

tr:nth-child(even) td.danger {
  background-color: #fc7777;
}

.danger:hover {
    background-color: #f58585;
    cursor: not-allowed;
}

.active {
    background-color: rgb(79, 226, 79);
}

.active:hover {
    background-color: lightgreen;
}
</style>
