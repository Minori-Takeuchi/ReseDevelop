<template>
  <div class="container flex jc-space-between">
    <div class="shop">
      <div class="flex">
        <button @click="$router.push('/')" class="back-btn">&lt;</button>
        <h2 class="shop-ttl">{{ shop.shop_name }}</h2>
      </div>
      <img class="shop-img" :src="`${$config.storageUrl}/uploads/${shop.img}`" alt="店舗写真">

      <div class="flex shop-item-wrap">
        <p class="shop-item">#{{ area }}</p>
        <p class="shop-item">#{{ genre }}</p>
      </div>
      <p v-show="shop.content !== 'null'" class="shop-content">{{ shop.content }}</p>
    </div>
    <div class="reserve">
      <ReserveForm
        :shop="shop"
        :courses="courses"
        @insertReservation="insertReservation"
        ></ReserveForm>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      param:this.$route.params.id,
      shop: {},
      courses: [],
      area: '',
      genre: '',
      }
  },
  methods: {
    // 店舗詳細情報取得
    async fetchShopData() {
      const { data } = await this.$axios.get(`/api/shop/${this.$route.params.id}`);
      this.shop = data.shop;
      this.area = data.area;
      this.genre = data.genre;
      this.courses = data.courses;
    },
    // 予約作成
    async insertReservation(sendData) {
      await this.$axios.post("/api/reserve/", sendData)
      try {
        this.$router.push("/detail/done");
      } catch(error) {
        console.log(error)
        alert('予約に失敗しました')
      }
    }
  },
  async created() {
    await this.fetchShopData();
  }
}
</script>
<style scoped>
.back-btn {
  width: 30px;
  height: 30px;
  font-size: larger;
  background-color: white;
  border: none;
  box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.231);
  border-radius: 3px;
}
.back-btn:hover {
  width: 30px;
  height: 30px;
  font-size: larger;
  background-color: rgb(250, 247, 241);
  border: none;
  box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.231);
  border-radius: 3px;
}
.shop {
  width: 55%;
}
.shop-ttl {
  font-size: 25px;
  margin: 2px 10px;
}
.shop-img {
  margin: 20px 5% 0 5%;
  width: 90%;
  box-shadow: 5px 5px 5px 0 rgba(0, 0, 0, .5);
}
.shop-item-wrap {
  margin-left: 10%;
}
.shop-item {
  margin: 20px 5px;
}
.shop-content {
  line-height: 24px;
}
.reserve {
  width: 40%;
}

@media screen and (max-width:768px) {
  .shop {
    width: 100%;
    margin: 20px auto;
  }
  .shop-ttl {
    font-size: 35px;
    margin: 0 20px;
  }
  .shop-item {
  margin: 20px 10px;
  font-size: 18px;
}
  .shop-content {
    font-size: 20px;
    line-height: 30px;
    width: 80%;
    margin: 20px auto;
  } 
  .reserve {
    width: 80%;
    margin: 0 auto;
  }
}
</style>