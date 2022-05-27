<template>
  <div>
    <div v-if="!flagMenu" style="width: 100%; z-index: 2000;">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <router-link class="navbar-brand" :to="{ path: '/profile' }">
            <img
              style="width: 35px; height: 35px; border-radius: 50%;"
              :src="uri + 'images/' + getImage"
              alt=""
            />
          </router-link>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <router-link :to="{ path: '/chat' }">
                  Chính
                </router-link>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
    <div v-else>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <router-link class="navbar-brand" :to="{ path: '/profile' }">
            <img
              style="width: 35px; height: 35px; border-radius: 50%;"
              :src="uri + 'images/' + getImage"
              alt=""
            />
          </router-link>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item mx-2">
                <router-link
                  class="nav-link active"
                  aria-current="page"
                  :to="{ path: '/community' }"
                  data-bs-toggle="tooltip"
                  data-bs-placement="bottom"
                  title="Thảo luận  "
                  style="width: 50px;"
                >
                  <svg
                    aria-hidden="true"
                    focusable="false"
                    data-prefix="fas"
                    data-icon="network-wired"
                    class="svg-inline--fa fa-network-wired fa-w-20"
                    role="img"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 640 512"
                  >
                    <path
                      fill="currentColor"
                      d="M640 264v-16c0-8.84-7.16-16-16-16H344v-40h72c17.67 0 32-14.33 32-32V32c0-17.67-14.33-32-32-32H224c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h72v40H16c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16h104v40H64c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h160c17.67 0 32-14.33 32-32V352c0-17.67-14.33-32-32-32h-56v-40h304v40h-56c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h160c17.67 0 32-14.33 32-32V352c0-17.67-14.33-32-32-32h-56v-40h104c8.84 0 16-7.16 16-16zM256 128V64h128v64H256zm-64 320H96v-64h96v64zm352 0h-96v-64h96v64z"
                    ></path>
                  </svg>
                </router-link>
              </li>
              <li class="nav-item mx-2">
                <router-link
                  class="nav-link active"
                  aria-current="page"
                  :to="{ path: '/chat' }"
                  data-bs-toggle="tooltip"
                  data-bs-placement="bottom"
                  title="Chát "
                  style="width: 50px;"
                >
                  <svg
                    aria-hidden="true"
                    focusable="false"
                    data-prefix="fas"
                    data-icon="comments"
                    class="svg-inline--fa fa-comments fa-w-18"
                    role="img"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 576 512"
                  >
                    <path
                      fill="currentColor"
                      d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1.9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9.7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z"
                    ></path>
                  </svg>
                </router-link>
              </li>
              <li class="nav-item active mx-2">
                <div class="dropdown">
                  <a
                    @click.prevent="readNotifi"
                    class="nav-link"
                    type="button"
                    id="dropdownMenuButton1"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    style="width: 40px;"
                  >
                    <svg
                      aria-hidden="true"
                      focusable="false"
                      data-prefix="fas"
                      data-icon="bell"
                      class="svg-inline--fa fa-bell fa-w-14"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 448 512"
                    >
                      <path
                        fill="currentColor"
                        d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z"
                      ></path>
                    </svg>
                    <span v-if="countNotRead > 0" class="badge bg-danger">
                      {{ countNotRead }}
                    </span>
                  </a>
                  <ul
                    v-if="dataNotifications"
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuButton1"
                  >
                    <li
                      style="
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        max-width: 100%;
                        padding: 5px;
                      "
                      v-for="(v, i) in dataNotifications"
                      :key="i"
                    >
                      <router-link
                        class="nav-link active"
                        aria-current="page"
                        :to="{
                          name: 'ShowPost',
                          params: {
                            id: v.data.id_post,
                            name: v.data.data,
                          },
                        }"
                      >
                        {{ v.data.data }}
                      </router-link>
                      <hr />
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
          <div class="d-flex">
            <div class="nav-item mx-2">
              <router-link
                class="nav-link active"
                aria-current="page"
                :to="{ path: '/storage' }"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="Kho lưu trữ  "
                style="width: 60px;"
              >
                <svg
                  aria-hidden="true"
                  focusable="false"
                  data-prefix="fas"
                  data-icon="boxes"
                  class="svg-inline--fa fa-boxes fa-w-18"
                  role="img"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 576 512"
                >
                  <path
                    fill="currentColor"
                    d="M560 288h-80v96l-32-21.3-32 21.3v-96h-80c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16h224c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16zm-384-64h224c8.8 0 16-7.2 16-16V16c0-8.8-7.2-16-16-16h-80v96l-32-21.3L256 96V0h-80c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16zm64 64h-80v96l-32-21.3L96 384v-96H16c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16h224c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16z"
                  ></path>
                </svg>
              </router-link>
            </div>

            <div style="position: relative;">
              <input
                type="text"
                class="form-control"
                v-model="data_search"
                placeholder="Tìm kiếm"
              />
              <div
                style="
                  position: absolute;
                  background: #fff;
                  padding: 10px;

                  width: 100%;
                "
              >
                <li
                  style="
                    border-bottom: 1px solid black;
                    text-overflow: ellipsis;
                  "
                  v-for="(v, i) in data"
                  :key="i"
                  class="nav-link"
                >
                  <router-link
                    class="nav-link active"
                    aria-current="page"
                    :to="{
                      name: 'ShowPost',
                      params: {
                        id: v.id,
                        name: v.slug_title,
                      },
                    }"
                  >
                    {{ v.title }}
                  </router-link>
                </li>
              </div>
            </div>
            <a :href="uri + 'logout'" class="nav-link active">Đăng xuất</a>
          </div>
        </div>
      </nav>
    </div>
    <br />

    <router-view></router-view>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      flagMenu: false,
      data_search: '',
      data: {},
      dataNotifications: {},
      countNotRead: 0,
    }
  },
  computed: {
    ...mapGetters(['getName', 'getEmail', 'getImage', 'getDetails']),
  },
  created() {
    this.getNotification()
  },
  methods: {
    async readNotifi() {
      return await axios
        .get(this.uri + 'api/read-notifications')
        .then((data) => {
          this.getNotification()
        })
    },
    async getNotification() {
      return await axios.get(this.uri + 'api/notifications').then((data) => {
        this.dataNotifications = data.data.data
        this.countNotRead = data.data.count_unread
      })
    },
    async getSerhPost() {
      return await axios
        .get(this.uri + 'api/post/?limit=5&search=' + this.data_search)
        .then((data) => {
          this.data = data.data.data.data
        })
        .catch((error) => {
          alert(Error)
        })
    },
  },
  watch: {
    $route(to, from) {
      if (
        to.fullPath === '/chat' ||
        to.fullPath === '/profile' ||
        to.name === 'ShowPost' ||
        to.name === 'ShowStorage' ||
        to.name === 'Storage' ||
        to.name === 'Community' ||
        to.fullPath === '/login-app-chat'
      ) {
        this.flagMenu = true
      } else {
        this.flagMenu = false
      }
    },
    data_search(value, valueOld) {
      if (value.trim() == '') {
        this.data = {}
      } else {
        this.getSerhPost()
      }
    },
  },
}
</script>
