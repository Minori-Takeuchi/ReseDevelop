<template>
  <div class="card">
    <img class="shop-img" :src="`${$config.storageUrl}/uploads/${shop.img}`" alt="店舗写真">
    <h2 class="shop-ttl">{{ shop.shop_name }}</h2>
    <div class="flex shop-contents">
      <p>#{{ shop.area }}</p>
      <p>#{{ shop.genre }}</p>
    </div>
    <div class="flex jc-space-between">
      <button class="btn shop-btn" @click="goShopPage">詳しく見る</button>
      <div v-show="shop.like_id" class="heart-pink" @click="unLike">♥</div>
      <div v-show="!shop.like_id" class="heart-gray" @click="like">♥</div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    shop: {},
  },
  methods: {
    goShopPage() {
      this.$router.push({ path: `/detail/${this.shop.id}` });
    },
    async like() {
      try {
        if (this.$store.state.user) {
          const likeData = {
            shop_id: this.shop.id,
            user_id: this.$store.state.user.uid
          }
          const { data } = await this.$axios.post("/api/like/", likeData);
          const sendData = {
            shop_id: data.data.shop_id,
            like_id: data.data.id
          };
          this.$emit('liked', sendData)

        } else {
          alert('ログインしてください')
        }
      } catch (error) {
        console.error(error);
      }
    },
    async unLike() {
      try {
        if (this.$store.state.user) {
          await this.$axios.delete("/api/like/" + this.shop.like_id);
          const sendData = this.shop.id
          this.$emit('unLiked', sendData)

        } else {
          alert('ログインしてください')
        }
      } catch (error){
        console.error(error);
      }
    }
  }
}
</script>

<style scoped>
.card {
  width: 100%;
  border-radius: 5px;
  box-shadow: 5px 5px 5px 0 rgba(0, 0, 0, .5);
  margin-bottom: 20px;
  background-color: white;
}
.shop-ttl {
  margin: 10px;
  font-size: large;
}
.shop-contents {
  margin-left: 10px;
}
.shop-img {
  width: 100%;
  border-radius: 5px 5px 0 0;
}
.shop-btn {
  width: 100px;
  height: 30px;
  margin: 10px;
  box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.231);
}
.heart-gray {
  color: rgb(168, 168, 168);
  cursor: pointer;
  font-size: 25px;
  margin: 10px;
}
.heart-gray:hover {
  color: rgb(155, 155, 155);
  cursor: pointer;
  font-size: 24px;
  margin: 10px;
}
.heart-pink {
  color: rgb(251, 93, 120);
  cursor: pointer;
  font-size: 30px;
  margin: 10px;
}
.heart-pink:hover {
  color: rgb(255, 70, 101);
  cursor: pointer;
  font-size: 29px;
  margin: 10px;
}
@media screen and (max-width:768px) {
  .card {
  width: 100%;
  margin-bottom: 40px;
}
  .shop-ttl {
  margin: 20px;
  font-size: 30px;
}
.shop-contents {
  margin: 20px;
  font-size: 20px;
}
.heart-gray {
  font-size: 40px;
  margin: 20px;
}
.heart-gray:hover {
  font-size: 42px;
  margin: 20px;
}
.heart-pink {
  font-size: 45px;
  margin: 20px;
}
.heart-pink:hover {
  font-size: 47px;
  margin: 20px;
}
.shop-btn {
  width: 150px;
  height: 40px;
  margin: 20px;
  font-size: 20px;
}
}
</style>