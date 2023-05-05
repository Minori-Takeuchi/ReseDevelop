<template>
  <div class="container">
    <p class="username">{{ $store.state.user.name }}さん</p>
    <div class="flex wrap">
      <div class="reservation">
        <p class="mypage-ttl">予約状況</p>
        <p class="reservation-ttl">本日の予約</p>
        <p v-if="todayReservations.length === 0" class="reservation-message">本日の予約はありません</p>
        <div class="reservation-item" v-for="(reservation,index) in todayReservations" :key="reservation.id">
          <Reservation
          class="reservation-card"
          :reservation="reservation"
          :index="index"
          @unReserved="handleUnReserved"
          ></Reservation>
        </div>
        <p class="reservation-ttl">明日以降の予約</p>
        <p v-if="futureReservations.length === 0" class="reservation-message">明日以降の予約はありません</p>
        <div class="reservation-item" v-for="(reservation,index) in futureReservations" :key="reservation.id">
          <Reservation
          class="reservation-card"
          :reservation="reservation"
          :index="index"
          @unReserved="handleUnReserved"
          ></Reservation>
        </div>
        <p class="reservation-ttl">過去の予約</p>
        <p v-if="pastReservations.length === 0" class="reservation-message">過去の予約はありません</p>
        <div class="reservation-item" v-for="(reservation,index) in pastReservations" :key="reservation.id">
          <Reservation
          class="reservation-card"
          :reservation="reservation"
          :index="index"
          @unReserved="handleUnReserved"
          @insertRating="insertRating"
          ></Reservation>
        </div>
      </div>
      <div class="shop">
        <p class="mypage-ttl">お気に入り店舗</p>
        <div class="shop-item flex jc-space-between">
          <div  class="shop-card" v-for="shop in shops" :key="shop.id">
            <ShopCard :shop="shop" @unLiked="handleUnLiked"></ShopCard>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      user: '',
      shops: [],
      pastReservations: [],
      todayReservations: [],
      futureReservations: [],
    }
  },
  
  methods: {
    // ユーザーデータ取得
    async fetchMyData() {
      const { data } = await this.$axios.get(`/api/mypage/${this.$store.state.user.uid}`);
      this.user = data.user;
      this.shops = data.shops;
      const reservationsData = data.reservations.sort((a, b) => {
        return new Date(a.date) - new Date(b.date)
      });
      const today = new Date();
      for (const reservation of reservationsData) {
        const date = new Date(reservation.date)
        if (date.toDateString() === today.toDateString()) {
          this.todayReservations.push(reservation)
        } else if (date < today) {
          this.pastReservations.push(reservation)
        } else if (date > today) {
          this.futureReservations.push(reservation)
        }
      }
    },
    // 予約削除
    handleUnReserved(sendData) {
      const futureIndex = this.futureReservations.findIndex(r => r.id === sendData)
      if (futureIndex >= 0) {
        this.futureReservations.splice(futureIndex, 1)
      }
      const todayIndex = this.todayReservations.findIndex(r => r.id === sendData)
      if (todayIndex >= 0) {
        this.todayReservations.splice(todayIndex, 1)
      }
      const pastIndex = this.pastReservations.findIndex(r => r.id === sendData)
      if (pastIndex >= 0) {
        this.pastReservations.splice(pastIndex, 1)
      }
    },
    // お気に入り解除
    handleUnLiked(sendData) {
      const index = this.shops.findIndex(s => s.id === sendData)
      this.shops.splice(index,1)
    },
    insertRating(sendData) {
      const index = this.pastReservations.findIndex(r => r.id === sendData.id)
      this.pastReservations.splice(index, 1, sendData)
      alert('評価頂きありがとうございました')
    }
  },
  async created() {
    await this.fetchMyData();
  }
  }

</script>

<style scoped>
.username {
  display: flex;
  justify-content: center;
  font-size: 24px;
  font-weight: bold;
  margin: 20px 0;
}
.mypage-ttl {
  margin: 20px;
  font-size: 20px;
  font-weight: bold;
}
.reservation {
  width: 40%;
}
.reservation-ttl {
  font-size: 18px;
  font-weight: bold;
  margin: 20px;
}
.reservation-message {
  margin: 25px;
}
.reservation-item {
  width: 80%;
}
.reservation-card {
  background-color: rgba(30, 119, 253, 1);
  color: white;
}
.shop{
  width: 60%;
}
.shop-item {
  margin-left: 20px;
}
.shop-card {
  width: calc(50% - 20px);
  margin-bottom: 10px;
}

@media screen and (max-width:768px) {
  .reservation {
  width: 80%;
  margin: 20px auto;
}
.shop{
  width: 80%;
  margin: 20px auto;
}
.shop-card {
  width: 90%;
}
}
</style>