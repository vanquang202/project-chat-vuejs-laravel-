<template>
  <div>
    <div v-if="!flag" class="chat-container">
      <div class="pro row align-center">
        <div class="col-sm-1">
          <img
            :src="uri + 'images/' + friend.image"
            style="width: 70px; height: 70px; border-radius: 50%;"
            alt=""
          />
        </div>
        <div class="col-sm-10">
          <h2>{{ friend.name }}</h2>
          <a @click.prevent="viewProfile" class="btn text-primary">
            View profile
          </a>
        </div>
      </div>
      <div class="end">
        <ul class="chat ul">
          <p
            class="text-primary"
            @click="viewMorePage"
            style="
              position: absolute;
              left: 50%;
              top: 0;
              transform: translateX(-50%);
              cursor: pointer;
            "
          >
            Xem thêm
          </p>
          <!---->
          <!---->

          <div v-for="(data, index) in dataChat" :key="index">
            <br />
            <!--                        <h3 :style="{-->
            <!--                                'float':data.me_id == id ? 'left' : 'right'-->
            <!--                                }">{{ data.user.name }}</h3>-->

            <div
              :style="{
                display: 'flex',
                'flex-direction': data.me_id == id ? 'row-reverse' : 'row',
                width: '100%',
                'align-items': 'center',
                gap: '10px',
              }"
            >
              <div v-if="data.me_id != id && data.view == 1">
                <small>Seen</small>
                <!--                                <img :src="uri+'images/'+data.user.image" style="width:15px ; height: 15px; border-radius:50%" alt="">-->
              </div>
              <div v-else style="width: 15px; height: 15px;"></div>
              <img
                v-if="
                  dataChat.length == index + 1 ||
                  data.me_id != dataChat[index + 1].me_id
                "
                :src="uri + 'images/' + data.user.image"
                style="width: 40px; height: 40px; border-radius: 50%;"
                alt=""
              />
              <div
                v-else
                style="width: 40px; height: 40px; border-radius: 50%;"
              ></div>
              <li v-if="data.img == 2" class="li">
                <p>{{ data.data }}</p>
                <a
                  target="_blank"
                  :href="uri + 'api/dowload-file?name-file=' + data.data"
                  style="width: 40px; float: right; text-decoration: none;"
                >
                  <svg
                    aria-hidden="true"
                    focusable="false"
                    data-prefix="fas"
                    data-icon="arrow-circle-down"
                    class="svg-inline--fa fa-arrow-circle-down fa-w-16"
                    role="img"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                  >
                    <path
                      fill="currentColor"
                      d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-143.6-28.9L288 302.6V120c0-13.3-10.7-24-24-24h-16c-13.3 0-24 10.7-24 24v182.6l-72.4-75.5c-9.3-9.7-24.8-9.9-34.3-.4l-10.9 11c-9.4 9.4-9.4 24.6 0 33.9L239 404.3c9.4 9.4 24.6 9.4 33.9 0l132.7-132.7c9.4-9.4 9.4-24.6 0-33.9l-10.9-11c-9.5-9.5-25-9.3-34.3.4z"
                    ></path>
                  </svg>
                </a>
              </li>
              <li v-if="!data.img" class="li">
                <p>{{ data.data }}</p>
              </li>

              <li v-if="data.img" class="li">
                <a :href="uri + 'images/' + data.data">
                  <img
                    style="max-width: 200px;"
                    :src="uri + 'images/' + data.data"
                    alt=""
                  />
                </a>
              </li>

              <br />
              <small>{{ data.time }}</small>
            </div>

            <br />
          </div>

          <!---->
          <!---->
        </ul>
      </div>
      <div style="background-color: white;" class="row">
        <!---->

        <form style="position: relative;" class="col-sm-1 formSend row">
          <!-- Show people chat -->
          <div
            v-if="showUserSend"
            style="position: absolute; bottom: 50px; width: 200px;"
            class="showImgSend"
          >
            <p>Có người đang nhập ...</p>
          </div>
          <!-- Show img -->
          <div style="position: absolute; top: -200px;" class="showImgSend">
            <img
              v-show="changeImg"
              :src="urlImg"
              width="200"
              height="200"
              alt=""
            />
          </div>
          <!-- Change img  -->
          <input
            style="display: none;"
            @change="changeImgF"
            id="imageClass"
            type="file"
            name="image"
            value=""
          />
          <!-- Icon img  -->
          <label style="width: 49px;" class="col-sm-5" for="imageClass">
            <svg
              aria-hidden="true"
              focusable="false"
              data-prefix="fas"
              data-icon="image"
              class="svg-inline--fa fa-image fa-w-16"
              role="img"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512 512"
            >
              <path
                fill="currentColor"
                d="M464 448H48c-26.51 0-48-21.49-48-48V112c0-26.51 21.49-48 48-48h416c26.51 0 48 21.49 48 48v288c0 26.51-21.49 48-48 48zM112 120c-30.928 0-56 25.072-56 56s25.072 56 56 56 56-25.072 56-56-25.072-56-56-56zM64 384h384V272l-87.515-87.515c-4.686-4.686-12.284-4.686-16.971 0L208 320l-55.515-55.515c-4.686-4.686-12.284-4.686-16.971 0L64 336v48z"
              ></path>
            </svg>
          </label>
          <div
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            class="col-sm-2"
            style="width: 45px;"
          >
            <svg
              aria-hidden="true"
              focusable="false"
              data-prefix="fas"
              data-icon="folder-plus"
              class="svg-inline--fa fa-folder-plus fa-w-16"
              role="img"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512 512"
            >
              <path
                fill="currentColor"
                d="M464,128H272L208,64H48A48,48,0,0,0,0,112V400a48,48,0,0,0,48,48H464a48,48,0,0,0,48-48V176A48,48,0,0,0,464,128ZM359.5,296a16,16,0,0,1-16,16h-64v64a16,16,0,0,1-16,16h-16a16,16,0,0,1-16-16V312h-64a16,16,0,0,1-16-16V280a16,16,0,0,1,16-16h64V200a16,16,0,0,1,16-16h16a16,16,0,0,1,16,16v64h64a16,16,0,0,1,16,16Z"
              ></path>
            </svg>
          </div>

          <!-- Modal -->
          <div
            class="modal fade"
            id="exampleModal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog">
              <form>
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      Tải lên thư mục của bạn
                    </h5>
                    <button
                      type="button"
                      id="close-modal"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <input
                      type="file"
                      id="send-file-chat"
                      class="form-control"
                    />
                    <small>Tối đa : 1000kb</small>
                    <br />
                    <small>Kiểu file : zip</small>
                    <br />
                    <input class="form-control" type="reset" value="Reset" />
                    <p class="text text-danger">{{ message_error }}</p>
                  </div>
                  <div id="show-storage" class="m-2 p-2">
                    <!-- <p
                      type="button"
                      @click.prevent="getStorageMe"
                      class="text-center btn-outline-info"
                    >
                      Truy cập kho lưu trữ của bạn
                    </p> -->
                    <!-- <li
                      v-if="storages"
                      v-for="(s, i) in storages"
                      :key="i"
                      class="item"
                    >
                      {{ s.name }}
                      <small
                        style="cursor: pointer;"
                        class="btn-success"
                        v-for="(sd, id) in s.detail_storage"
                        :key="id"
                      >
                        {{ sd.name }} - size : {{ sd.size }}
                        <span
                          @click,prevent="sendFileChat(sd.url)"
                          class="btn-primary rounded-pill"
                        >
                          Gửi
                        </span>
                        <br />
                      </small>
                    </li> -->
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                    >
                      Thoát
                    </button>
                    <button
                      v-if="!flagFileSend"
                      type="button"
                      @click.prevent="sendFileChat"
                      class="btn btn-primary"
                    >
                      Gửi
                    </button>
                    <button v-else type="button" class="btn btn-primary">
                      Đang gửi ...
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </form>

        <!---->
        <!---->

        <form
          style="background-color: white;"
          class="col-sm-11 row"
          @submit.prevent="chatData"
        >
          <!--                    <input class="col-sm-10 data_chat" style="border-radius: 20px;-->
          <!--                     height: 50px;outline:none" placeholder="Chat">-->
          <textarea
            :disabled="load"
            @change="usereSend"
            spellcheck="false"
            v-model="data"
            class="col-sm-10 data_chat"
            style="border-radius: 20px; height: 50px; outline: none;"
          ></textarea>
          <button
            :disabled="load"
            v-show="!load"
            style="border-radius: 20px; margin-left: -35px;"
            type="submit"
            class="col-sm-2 chat_send"
          >
            Chat
          </button>
          <button
            :disabled="load"
            v-show="load"
            style="border-radius: 20px; margin-left: -35px;"
            class="col-sm-2 chat_send"
          >
            Đang send ...
          </button>
        </form>

        <!---->
      </div>
    </div>
    <div v-else class="text-center">
      <img
        src="https://i.pinimg.com/originals/f6/65/6a/f6656aa6fdb6b8f905dea0bcc2d71dd8.gif"
        width="500"
        alt=""
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  name: 'Chat',
  beforeRouteUpdate(to, from, next) {
    this.flag = true
    next()
  },
  data() {
    return {
      id: 0,
      page: 1,
      data: '',
      urlImg: '',
      dataChat: [],
      friend: [],
      storages: [],
      flag: false,
      load: false,
      changeImg: false,
      showUserSend: false,
      flagFileSend: false,
      message_error: null,
      id_auth: Math.floor(Math.random() * 1000),
    }
  },

  created() {
    this.id = this.$route.params.id
    this.getRoom()
    let that = this

    Echo.channel('channel-send-' + id_me).listen('SendChat', function () {
      that.getRoom()
    })
    Echo.channel('channel-send-' + this.id).listen('SendChat', function () {
      that.load = true
      that.getRoom()
    })
    Echo.channel('user-input-' + id_me).listen('UserInput', function () {
      that.showUserSend = true
      setTimeout(function () {
        that.showUserSend = false
      }, 1000)
    })
  },
  methods: {
    async sendFileChat(chat) {},
    async viewMorePage() {
      this.page++
      this.getRoom()
    },
    async getStorageMe() {
      try {
        const data = await axios.get(this.uri + 'api/storage?detail_storage')
        this.storages = data.data.data
        console.log(this.storages)
      } catch (error) {
        alert(error)
      }
    },
    async sendFileChat() {
      var file = document.getElementById('send-file-chat').files[0]
      var formData = new FormData()
      formData.append('id', this.id)
      formData.append('file_send', file)
      this.flagFileSend = true
      return await axios
        .post(this.uri + 'api/file-send-chat', formData)
        .then((res) => {
          this.message_error = res.data.message?.file_send[0]
            ? res.data.message?.file_send[0]
            : null
          this.getRoom()
        })
        .finally(() => {
          document.getElementById('close-modal').click()
          this.flagFileSend = false
        })
    },
    async getRoom() {
      return await axios
        .get(this.uri + 'api/room/' + this.id + '?page=' + this.page)
        .then((res) => {
          if (res.data.code == 500) {
            this.$router.push({ path: '/chat' })
          }
          this.dataChat = res.data.data
          this.friend = res.data.friend
          this.load = false
          this.flag = false
        })
        .catch((err) => {
          console.log(err)
        })
    },
    async sendForm(data) {
      this.load = true
      var file = document.getElementById('imageClass').files[0]

      var formData = new FormData()

      formData.append('id', this.id)
      formData.append('id_auth', this.id_auth)
      if (data.trim() != '') {
        formData.append('data', data)
      }
      if (file) {
        formData.append('image', file)
        this.changeImg = false
        document.getElementById('imageClass').value = null
      }
      let that = this
      return await axios
        .post(this.uri + 'api/room', formData)
        .then((res) => {
          that.data = ''
          getRoom()
        })
        .catch((err) => {})
    },
    async usereSend() {
      return await axios
        .get(this.uri + 'api/event-chat-input/' + this.id)
        .then((res) => {})
        .catch((err) => {})
    },

    chatData() {
      this.sendForm(this.data)
    },
    changeImgF(e) {
      const file = e.target.files[0]
      this.changeImg = true
      this.urlImg = URL.createObjectURL(file)
      console.log(this.urlImg)
    },
    viewProfile() {
      this.$router.push({
        name: 'ProfileViewYou',
        params: { id: this.id },
      })
    },
  },
  watch: {
    $route(to, from) {
      this.id = to.params.id
      this.getRoom()
    },
  },
  computed: {
    ...mapGetters(['getName', 'getEmail', 'getImage', 'getDetails']),
  },
}
</script>

<style scoped>
html {
  width: 100%;
  height: 100%;
}
.chat-container {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  width: 75%;
  height: 80%;
}

.end {
  background: #fff;
  margin: 0 auto;
  width: 100%;
  height: 100%;
  /*overflow-x: hidden;*/
  /*overflow-y: auto;*/
  overflow: auto;
  transform: rotate(180deg);
  direction: rtl;
  text-align: left;
}
.ul {
  width: 100%;
  overflow: hidden;
  transform: rotate(180deg);
  padding: 10px;
}
.li {
  /* display: inline-block; */
  max-width: 50%;
  white-space: pre-line;
  gap: 20px;
  list-style: none;
  border-radius: 10px;
  box-shadow: 2px 2px 12px black;
}
.li p {
  height: auto;
  padding: 10px;
}
</style>
