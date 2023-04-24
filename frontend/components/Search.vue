<template>
  <div class="search">
    <select v-model="searchData.area" @change="updateAreaData" class="search-tag">
      <option value="">All Area</option>
      <option v-for="area in areas" :key="area.id" :value="area.area">{{ area.area }}</option>
    </select>
    <div class="border">|</div>
    <select v-model="searchData.genre" @change="updateGenreData" class="search-tag">
      <option value="">All Genre</option>
      <option v-for="genre in genres" :key="genre.id" :value="genre.genre">{{ genre.genre }}</option>
    </select>
    <div class="border">|</div>
    <img v-show="shouldHideImage" src="~/assets/img/search.png" class="search-img">
    <input type="text" v-model="searchData.shop_name" placeholder="     search" @change="updateShopNameData" class="search-form"  @focus="onInputFocus" @blur="onInputBlur">
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchData: {
        area: '',
        genre: '',
        shop_name: '',
      },
      isInputFocused: false
    }
  },
  computed: {
    areas() {
      return this.$store.state.areas;
    },
    genres() {
      return this.$store.state.genres;
    },
    inputIsEmpty() {
      return this.searchData.shop_name.trim() === ''
    },
    shouldHideImage() {
      return this.inputIsEmpty && !this.isInputFocused
    }
  },
  methods: {
    updateAreaData() {
      this.$store.commit('setAreaData', this.searchData.area);
    },
    updateGenreData() {
      this.$store.commit('setGenreData', this.searchData.genre);
    },
    updateShopNameData() {
      this.$store.commit('setShopNameData', this.searchData.shop_name);
    },
    onInputFocus() {
      this.isInputFocused = true
    },
    onInputBlur() {
      this.isInputFocused = false

    }
  }
}
</script>
<style scoped>
.search {
  display: flex;
  height: 40px;
  background-color: white;
  align-items: center;
  justify-content: space-around;
  margin-right: 20px;
  border-radius: 5px;
}
.border {
  color: rgb(147, 146, 146);
}
.search-tag {
  width: 110px;
  height: 30px;
  border:none;
  color: rgb(114, 114, 114);
  margin: 0 10px;
  background-color: white;
}
.search-form {
  position: relative;
  width: 350px;
  height: 30px;
  margin: 0 10px;
  border: none;
}
.search-img {
  position: absolute;
  right:370px;
  z-index: 1;
} 
@media screen and (max-width:768px) {
  .search-tag {
  width: 80px;
  height: 30px;
  border:none;
  font-size: small;
  color: rgb(114, 114, 114);
  margin: 0 10px;
  background-color: white;
}

  .search-form {
  position: relative;
  width: 250px;
  height: 30px;
  font-size: small;
  margin: 0 10px;
  border: none;
}

  .search-img {
  display: none;
  } 
}
</style>