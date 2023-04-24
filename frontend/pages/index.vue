<template>
  <div class="container">
    <div class="flex jc-space-between">
      <div  class="shop-card" v-for="shop in filteredShopData" :key="shop.id">
        <ShopCard :shop="shop" @liked="handleLiked" @unLiked="handleUnLiked"></ShopCard>
      </div>
    </div>
  </div>
</template>

<script>
import { getAuth, isSignInWithEmailLink, signInWithEmailLink } from "firebase/auth";
  export default {
    data(){
      return {
        shops:[],
        areas:[],
        genres: [],
      }
  },
  methods: {
    // 店舗一覧取得
    async getShopData() {
      const { data } = await this.$axios.get("/api/shop");
      this.$store.commit('setAreas',data.areas)
      this.$store.commit('setGenres',data.genres)
      this.shops = data.shops;
    },
    // お気に入り追加
    handleLiked(sendData) {
      const shop = this.shops.find(s => s.id === sendData.shop_id)
      shop.like_id = sendData.like_id;
    },
    // お気に入り解除
    handleUnLiked(sendData) {
      const shop = this.shops.find(s => s.id === sendData)
      shop.like_id = null
    },
    // メールアドレスでログイン
    async mailSignIn() {
      const auth = getAuth();
      if (isSignInWithEmailLink(auth, window.location.href)) {

        var email = window.localStorage.getItem('emailForSignIn');
        if (!email) {
          email = window.prompt('Please provide your email for confirmation');
        }
        try {
          signInWithEmailLink(auth, email, window.location.href)
            .then((result) => {
              console.log(result.user.uid)
              this.$axios.get("/api/user/" + result.user.uid)
                .then((response) => {
                  const user = {
                    uid: result.user.uid,
                    email: result.user.email,
                    name: response.data.name,
                    role: response.data.role,
                  };
                  this.$store.dispatch('loginAction', user)
                  alert('ログインしました')
                  window.localStorage.removeItem('emailForSignIn');
                })
            })
        } catch(error) {
            console.log(error)
            alert('ログインに失敗しました')
        }
      }
    },
    async fetchLikesData() {
      const { data } = await this.$axios.post("/api/likes", {
        user_id: this.$store.state.user.uid
      })
      const likes = data.likes;
      likes.forEach(like => {
        const shop = this.shops.find(shop => shop.id === like.shop_id)
        if (shop) {
          shop.like_id = like.id
        }
      });
    }
  },
  async created() {
    await this.getShopData();
    await this.mailSignIn();
    if (this.$store.state.user) {
      await this.fetchLikesData();
    }
  },
  computed: {
    area() {
      return this.$store.state.areaData;
    },
    genre() {
      return this.$store.state.genreData;
    },
    shop_name() {
      return this.$store.state.shop_nameData;
    },
    // 店舗検索
    filteredShopData() {
      const area = this.area;
      const genre = this.genre;
      const shop_name = this.shop_name;
      if (area || genre || shop_name) {
        return this.shops.filter(shopData => {
          return (
            (area === '' || shopData.area === area) &&
            (genre === '' || shopData.genre === genre) &&
            (shop_name === '' || shopData.shop_name.includes(shop_name))
          )
        });
      } else {
        return this.shops;
      }
    }
  }
  }
</script>

<style scoped>
.shop-card {
  width: 23%;
}
@media screen and (max-width:768px) {
  .shop-card {
  width: 80%;
  margin: 0 auto;
}
}
</style>
