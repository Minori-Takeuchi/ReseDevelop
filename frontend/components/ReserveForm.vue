<template>
  <div class="form">
    <validation-observer ref="obs" v-slot="ObserverProps">
      <p class="form-ttl">予約</p>
        <validation-provider v-slot="ProviderProps" rules="required">
          <input type="date" name="予約日" v-model="date" :min="today" class="form-date">
          <div class="error">{{ ProviderProps.errors[0] }}</div>
      </validation-provider>
      <validation-provider v-slot="ProviderProps" rules="required">
        <select name="予約時間" v-model="time" :disabled="!date" class="form-tag">
          <option v-for="option in availableTimes" :value="option" :key="option">{{ option }}</option>
        </select>
        <div class="error">{{ ProviderProps.errors[0] }}</div>
      </validation-provider>
      <validation-provider v-slot="ProviderProps" rules="required">
        <select name="人数" v-model="number" class="form-tag">
          <option v-for="n in Array.from({ length: 10 }, (_, i) => i + 1)" :value="n" :key="n">{{ n }}人</option>
        </select>
        <div class="error">{{ ProviderProps.errors[0] }}</div>
      </validation-provider>
      <validation-provider v-slot="ProviderProps" rules="required">
        <select name="コース" v-model="course_id" class="form-tag">
          <option v-for="course in courses" :key="course" :value="course.id">{{ course.course }}</option>
        </select>
        <div class="error">{{ ProviderProps.errors[0] }}</div>
      </validation-provider>
      <div class="form-data">
        <table>
          <tr class="reserve-item">
            <td>Shop</td>
            <td>{{ shop.shop_name }}</td>
          </tr>
          <tr class="reserve-item">
            <td>Date</td>
            <td>{{ date }}</td>
          </tr>
          <tr class="reserve-item">
            <td>Time</td>
            <td>{{ time | slice(0, 5) }}</td>
          </tr>
          <tr class="reserve-item">
            <td>Number</td>
            <td>{{ number }}<span v-show="number">人</span></td>
          </tr>
          <tr class="reserve-item">
            <td>Course</td>
            <td>{{ selectCourse.course }}　<span v-show="selectCourse.price !== undefined">{{ selectCourse.price }}円</span></td>
          </tr>
        </table>
      </div>
      <button @click="insertReservation" :disabled="ObserverProps.invalid || !ObserverProps.validated" class="form-btn">予約する</button>
    </validation-observer>
  </div>
</template>
<script>
export default {
  data() {
    return {
      today: '',
      availableTimes: [],
      date: '',
      time: '',
      number: '',
      course_id: '',
    }
  },
  props: {
    shop:{},
    courses:[],
  },
  methods: {
    // 予約日時バリデート
    dateValidate() {
      const todaySet = new Date();
      const YYYY = todaySet.getFullYear();
      const MM = ('00' + (todaySet.getMonth() + 1)).slice(-2);
      const DD = ('00' + todaySet.getDate()).slice(-2);
      const HH = ('00' + todaySet.getHours()).slice(-2);
      const mm = ('00' + todaySet.getMinutes()).slice(-2);
      this.today = YYYY + '-' + MM + '-' + DD;

      // もし今日の日付を選択している場合
      if (this.date === this.today) {
        const currentHour = parseInt(HH);
        const currentMinute = parseInt(mm);
        const availableTimes = [];

        const startHour = currentHour < 11 || (currentHour === 11 && currentMinute < 30) ? 11 :
          currentHour >= 20 || (currentHour === 19 && currentMinute >= 30) ? 21 : currentHour;
        let startMinute = currentMinute < 30 ? 30 : 0;

        for (let hour = startHour; hour < 21; hour++) {
          if (hour === startHour && startMinute === 30) {
            availableTimes.push(hour + ':30');
          } else {
            const timeString = hour + ':' + ('00' + startMinute).slice(-2);
            const currentTime = new Date();
            const selectedTime = new Date(`${this.today}T${timeString}:00`);
            if (selectedTime >= currentTime) {
              availableTimes.push(timeString);
              availableTimes.push(hour + ':' + ('00' + (startMinute + 30)).slice(-2));
              startMinute = 0;
            }
          }
        }
        this.availableTimes = availableTimes;
      } else {
        // 今日の日付以外の場合はすべての時間を使用可能とする
        this.availableTimes = [
          '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
          '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
          '17:00', '17:30', '18:00', '18:30', '19:00', '19:30',
          '20:00','20:30'
        ];
      }
    },
    // 予約作成
    insertReservation() {
      if (this.$store.state.user) { 
        const sendData = {
          user_id: this.$store.state.user.uid,
          course_id: this.course_id,
          date: this.date,
          time: this.time,
          number: this.number
        }
        console.log(sendData)
        const selectedDateTime = new Date(`${this.date}T${this.time}:00`);
        const currentDateTime = new Date();
        if (selectedDateTime < currentDateTime) {
          alert('過去の日時は選択できません');
          return;
        }
          this.$emit("insertReservation", sendData);
        } else {
          alert('ログインしてください')
        }
    }
  },
  computed: {
    selectCourse() {
      return this.courses.find(course => course.id === this.course_id) || {};
    },
  },
  watch: {
    date() {
      this.dateValidate();
    },
  },
  async created() {
    await this.dateValidate();
  }
}
</script>
<style scoped>
.form {
  background-color: rgb(16, 102, 230);
  border-radius: 5px;
  box-shadow: 5px 5px 5px 0 rgba(0, 0, 0, .5);
}
.form-ttl {
  display: inline-block;
  width: 100%;
  color: white;
  font-size: large;
  margin: 5%;
}
.form-date {
  width: 40%;
  height: 30px;
  font-size: 18px;
  border-radius: 5px;
  border:none;
  margin: 10px 30px;
}
.form-tag {
  width: 80%;
  height: 30px;
  font-size: 18px;
  border-radius: 5px;
  border:none;
  background-color: white;
  margin: 10px 30px;
}
.form-data {
  width: 75%;
  color: white;
  background-color: rgb(79, 142, 224);
  margin: 30px 30px 80px 30px;
  padding: 5% 0 5% 5%;
}
.reserve-item {
  height: 30px;
}
.reserve-item td:first-child {
  width: 100px;
}
.error {
  text-align: center;
  margin: 10px 0;
  color: white;
}
.form-btn {
  background-color: rgb(0, 43, 232);
  border-radius: 5px;
  border: none;
  color: white;
  width: 100%;
  height: 50px;
  cursor: pointer;
}
.form-btn:hover {
  background-color: rgb(0, 37, 198);
  border-radius: 5px;
  border: none;
  color: white;
  width: 100%;
  height: 50px;
  cursor: pointer;
}

@media screen and (max-width:768px) {
  .form-ttl {
    font-size: 20px;
    margin: 20px 30px;
  }
  .error {
    font-size: large;
    margin: 10px 0;
  }
  .form-date {
    margin: 10px 40px;
}
  .form-tag {
    width: 80%;
    margin: 10px 40px;
  }
  .form-data {
    margin: 30px 30px 40px 40px;
    padding: 5% 0 5% 5%;
    font-size: 24px;
  }
  .reserve-item td:first-child {
    width: 110px;
  }
  .form-btn {
    font-size:larger;
}
}
</style>