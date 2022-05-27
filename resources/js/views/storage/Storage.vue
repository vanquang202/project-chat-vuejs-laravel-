<template>
  <div>
    <div class="mx-5 container-fluid">
      <div class="row">
        <div class="col-sm-2">
          <div class="input-group mb-3">
            <input
              type="text"
              placeholder="Tên kho lưu trữ "
              v-model="nameStorage"
              class="form-control"
            />
            <button
              class="btn btn-outline-primary"
              @click.prevent="createStorage"
              type="button"
              id="button-addon2"
            >
              Tạo
            </button>
          </div>
        </div>
        <div class="col-sm-10">
          <div class="mx-2">
            <h2>
              Kho lưu trữ
              <button
                @click.prevent="reload"
                style="
                  transition: all 2s;
                  width: 50px;
                  background: none;
                  border: none;
                  list-style: none;
                "
                :class="{ 'active-r': flagReload }"

              >
                <svg
                  aria-hidden="true"
                  focusable="false"
                  data-prefix="fas"
                  data-icon="sync"
                  class="svg-inline--fa fa-sync fa-w-16"
                  role="img"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 512 512"
                >
                  <path
                    fill="currentColor"
                    d="M440.65 12.57l4 82.77A247.16 247.16 0 0 0 255.83 8C134.73 8 33.91 94.92 12.29 209.82A12 12 0 0 0 24.09 224h49.05a12 12 0 0 0 11.67-9.26 175.91 175.91 0 0 1 317-56.94l-101.46-4.86a12 12 0 0 0-12.57 12v47.41a12 12 0 0 0 12 12H500a12 12 0 0 0 12-12V12a12 12 0 0 0-12-12h-47.37a12 12 0 0 0-11.98 12.57zM255.83 432a175.61 175.61 0 0 1-146-77.8l101.8 4.87a12 12 0 0 0 12.57-12v-47.4a12 12 0 0 0-12-12H12a12 12 0 0 0-12 12V500a12 12 0 0 0 12 12h47.35a12 12 0 0 0 12-12.6l-4.15-82.57A247.17 247.17 0 0 0 255.83 504c121.11 0 221.93-86.92 243.55-201.82a12 12 0 0 0-11.8-14.18h-49.05a12 12 0 0 0-11.67 9.26A175.86 175.86 0 0 1 255.83 432z"
                  ></path>
                </svg>
              </button>
            </h2>

            <hr />
            <div class="row">
              <div v-for="(s, i) in storages" :key="i" class="col-sm-3">
                <div class="card" style="width: 18rem;">
                  <router-link
                    :to="{ name: 'ShowStorage', params: { id: s.id } }"
                    style="text-decoration: none; cursor: pointer;"
                    class="card-body"
                  >
                    <h5 class="card-title">{{ s.name }}</h5>
                    <small>{{ s.detail_storage_count }} Bản ghi</small>
                  </router-link>
                </div>
              </div>
              <router-view></router-view>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios'
export default {
  data() {
    return {
      nameStorage: null,
      flagReload: false,
      storages: [],
    }
  },
  created() {
    this.getStorage()
  },
  methods: {
    async reload() {
      this.flagReload = true
      this.getStorage()
    },
    async getStorage() {
      return await axios
        .get(this.uri + 'api/storage')
        .then((res) => {
          this.storages = res.data.data
        })
        .catch((err) => {
          alert(err.response.data.message)
        })
        .finally(() => {
          this.flagReload = false
        })
    },
    async createStorage() {
      let data = new FormData()
      data.append('name', this.nameStorage == null ? '' : this.nameStorage)
      return await axios
        .post(this.uri + 'api/storage', data)
        .then((res) => {
          this.getStorage()
        })
        .catch((err) => {
          alert(err.response.data.message)
        })
    },
  },
}
</script>
<style scoped>
.active-r {
   animation: mymove 5s infinite;
}
@keyframes mymove {
  50% {transform: rotate(180deg);}
}
</style>
