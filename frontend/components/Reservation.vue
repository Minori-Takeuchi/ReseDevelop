<template>
  <div class="card">
    <div class="card-item flex jc-space-between">
      <p class="card-ttl">予約{{ index + 1 }}</p>
      <div>
        <img src="~/assets/img/qrcode.png" alt="QRコード表示" @click="goCodePage" class="qr-btn">
        <img src="~/assets/img/cancel.png" alt="予約取り消し" @click="deleteReservation" class="cancel-btn">
      </div>
    </div>
    <table>
      <tr class="card-content">
        <td>Shop</td>
        <td>{{ reservation.shop }}</td>
      </tr>
      <tr class="card-content">
        <td>Date</td>
        <td>{{ reservation.date }}</td>
      </tr>
      <tr class="card-content">
        <td>Time</td>
        <td>{{ reservation.time }}</td>
      </tr>
      <tr class="card-content">
        <td>Number</td>
        <td>{{ reservation.number }}人</td>
      </tr>
      <tr class="card-content">
        <td>Course</td>
        <td>{{ reservation.course }} {{ reservation.price }}円</td>
      </tr>
    </table>
    <button v-if="new Date(reservation.date) >=  new Date(this.tomorrow) && !changeReservation" 
            @click="toggleChangeReservation"
            class="reservation-btn">予約を変更する</button>
    <button v-if="changeReservation" @click="toggleChangeReservation" class="reservation-btn">変更せずに戻る</button>
    <button v-if="!changeRating &&
            !reservation.rating &&
            new Date(this.reservation.date) < new Date(new Date().setHours(0, 0, 0, 0))"
            @click="toggleChangeRating" class="reservation-btn">評価する</button>
    <button v-if="changeRating" @click="toggleChangeRating" class="btn reservation-btn">評価せずに戻る</button>
    <button v-if="new Date(reservation.date) >= new Date(this.tomorrow) && !changePayment" @click="toggleChangePayment" class="reservation-btn">事前決済する</button>
    <button v-if="changePayment" @click="toggleChangePayment" class="reservation-btn">決済せずに戻る</button>
    <div v-show="reservation.rating"
    @mouseover="showComment = true"
    @mouseleave="showComment = false"
    class="flex rated">
    <div  v-for="i in reservation.rating" :key="i" class="rated-ster">★</div>
    <p class="rated-number">{{ reservation.rating }}</p>
  </div>
  <p v-if="showComment" class="reservation-comment">{{ reservation.comment }}</p>
  <!-- 予約変更フォーム -->
  <div v-if="changeReservation" class="change-reservation">
      <validation-observer ref="obs" v-slot="ObserverProps">
        <p class="form-ttl">※予約は日時・人数のみ変更できます</p>
        <validation-provider v-slot="ProviderProps" rules="required">
          <input type="date" name="予約日" v-model="date" :min="tomorrow" class="form-date">
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
        <button @click="updateReservation" :disabled="ObserverProps.invalid || !ObserverProps.validated" class="reservation-btn update-btn">予約変更</button>
      </validation-observer>
    </div>
    <Stripe v-if="changePayment" :reservation="reservation"></Stripe>
    <div v-show="changeRating">
      <p v-if="form.rating" class="rating-check">評価：{{ form.rating }}を選択しました</p>
      <div class="rating-btn flex">
        <button @click="selectRating(1)" :class="{ 'active': form.rating >= 1 }">★</button>
        <button @click="selectRating(2)" :class="{ 'active': form.rating >= 2 }">★</button>
        <button @click="selectRating(3)" :class="{ 'active': form.rating >= 3 }">★</button>
        <button @click="selectRating(4)" :class="{ 'active': form.rating >= 4 }">★</button>
        <button @click="selectRating(5)" :class="{ 'active': form.rating >= 5 }">★</button>
      </div>
      <textarea v-model="form.comment" placeholder="コメント(任意:300文字以内)" maxlength="300" class="rating-comment"></textarea>
      <button @click="insertRating" class="reservation-btn update-btn">評価</button>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    reservation: {},
    index: {},
  },
  data() {
    return {
      changeReservation: false,
      changeRating: false,
      changePayment: false,
      showComment: false,
      tomorrow: '',
      availableTimes: [],
      date: '',
      time: '',
      number: '',
      course_id: '',
      form: {
        rating: '',
        comment: '',

      }
    }
  },
  methods: {
    // 予約削除
    async deleteReservation() {
      try {
        if (this.$store.state.user) {
          const confirmed = window.confirm('予約を取り消しますか？')
          if (confirmed) {
            const sendData = this.reservation.id;
            await this.$axios.delete("/api/reserve/" + sendData);
            this.$emit('unReserved', sendData)
          } else {
            return false
          }
        } else {
          alert('ログインしてください')
        }
      } catch (error) {
        console.error(error);
      }
    },
    toggleChangeReservation() {
      this.changeReservation = !this.changeReservation
    },
    toggleChangeRating() {
      this.changeRating = !this.changeRating
    },
    toggleChangePayment() {
      this.changePayment = !this.changePayment
    },
    // 予約日時バリデート
    dateValidate() {
      const tomorrowSet = new Date();
      tomorrowSet.setDate(tomorrowSet.getDate() + 1);
      const YYYY = tomorrowSet.getFullYear();
      const MM = ('00' + (tomorrowSet.getMonth() + 1)).slice(-2);
      const DD = ('00' + tomorrowSet.getDate()).slice(-2);
      const HH = ('00' + tomorrowSet.getHours()).slice(-2);
      const mm = ('00' + tomorrowSet.getMinutes()).slice(-2);
      this.tomorrow = YYYY + '-' + MM + '-' + DD;

      this.availableTimes = [
        '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
        '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
        '17:00', '17:30', '18:00', '18:30', '19:00', '19:30',
        '20:00', '20:30',
      ];
    },
      // 予約変更（明日以降の予約のみ）
    async updateReservation() {
      if (this.$store.state.user) {
        try {
          const sendData = {
            id: this.reservation.id,
            date: this.date,
            time: this.time,
            number: this.number
          }
          const selectedDateTime = new Date(`${this.date}T${this.time}:00`);
          const currentDateTime = new Date();
          if (selectedDateTime <= currentDateTime) {
            alert('明日以降の日時を選択して下さい');
            return;
          }
          const confirmed = window.confirm('予約を変更しますか？')
          if (confirmed) {
            await this.$axios.put("/api/reserve/" + this.reservation.id, sendData)
            this.$router.push("/mypage/update");
          } else {
            return false
          }
        } catch (error) {
          console.log(error)
          alert('予約の変更に失敗しました')
        }
      } else {
        alert('ログインしてください')
      }
    },
    // 5段階評価選択
    selectRating(rating) {
      this.form.rating = rating
    },
    // 評価・コメント作成（昨日以前の予約のみ）
    async insertRating() {
        const todaySet = new Date();
        const YYYY = todaySet.getFullYear();
        const MM = ('00' + (todaySet.getMonth() + 1)).slice(-2);
        const DD = ('00' + todaySet.getDate()).slice(-2);
        const today = YYYY + '-' + MM + '-' + DD;
      if (new Date(this.reservation.date) >= today) {
        alert('予約日の翌日より評価が可能です')
        return;
      }
      try {
        if (!this.form.rating) {
          alert('★をクリックして評価をお願いします')
          return
        }
        const { data } = await this.$axios.post("/api/rating", {
          reservation_id: this.reservation.id,
          rating: this.form.rating,
          comment: this.form.comment
        })
        const sendData = data.rating
        this.$emit('insertRating',sendData)
        this.changeRating = false
      } catch (error) {
        console.log(error)
      }
    },
    goCodePage() {
      this.$router.push({ path: `/mypage/qrcode/${this.reservation.id}` });
    }
    },
    watch: {
      date() {
        this.dateValidate();
      }
    },
    async created() {
      await this.dateValidate();
  },
  }
</script>
<style scoped>
.card {
  width: 100%;
  height: 100%;
  border-radius: 5px;
  box-shadow: 10px 10px 10px 0 rgba(0, 0, 0, .5);
  margin-bottom: 20px;
  padding: 20px;
}
.card-item {
  margin-bottom: 20px;
}
.card-ttl {
  position: relative;
  left: 45px;
  top:10px;
  font-size: larger;
}
.card-ttl::before {
  position: absolute;
  content:url("~/assets/img/clock.png");
  left:-45px;
  top: -8px;
}
.card-content {
  height: 24px;
}
.card-content td:first-child {
  width: 80px;
}
.qr-btn {
  cursor: pointer;
  width: 32px;
  filter: invert(100%);
}
.cancel-btn {
  cursor: pointer;
}
.rating-check {
  margin-top: 15px;
}
.rating-btn {
  margin: 10px 0;
}
.rating-btn button {
  color: rgb(189, 189, 189);
  border: none;
  background-color: rgba(189, 189, 189, 0);
  font-size: 18px;
}
.rated {
  margin-top: 5px;
}
.rating-btn button.active,
.rated-ster {
  color: yellow;
  border: none;
  background-color: rgba(189, 189, 189, 0);
  font-size: 20px;
  cursor: pointer;
}
.rated-number {
  font-size: 20px;
  font-weight: bold;
  margin-left: 2px;
}
.rating-comment {
  width: 100%;
  height: 100px;
  border-radius: 5px;
  padding: 5px;
}
.reservation-btn {
  background-color: rgb(72, 145, 254);
  border-radius: 5px;
  border: none;
  color: white;
  cursor: pointer;
  box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.231);
  height: 30px;
  margin-top: 5px;
}
.reservation-btn:hover {
  background-color: rgb(19, 112, 252);
}
.form-ttl {
  margin: 20px auto;
}
.error {
    margin: 10px 0 10px 30px;
  }
.form-date {
  width: 40%;
  height: 24px;
  border-radius: 5px;
  border:none;
  margin: 5px 30px;
}
.form-tag {
  width: 80%;
  height: 24px;
  border-radius: 5px;
  border:none;
  background-color: white;
  margin: 5px 30px;
}
.update-btn {
  margin: 20px 0 0 70%;
  width: 80px;
  display: block;
}
@media screen and (max-width:768px) {
  .card {
    padding: 40px;
  }
  .card-content {
    font-size: 18px;
    height: 30px;
  }
  .card-content td:first-child {
  width: 100px;
}
  .qr-btn {
  margin-right: 20px;
  }
}
</style>