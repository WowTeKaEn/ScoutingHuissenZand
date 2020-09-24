<template>
  <b-card class="mb-3" v-if="returned">
    <h1>Foto's</h1>
    <h3>Albums</h3>
    <label for="new-album-name">Nieuw album aanmaken</label>
    <div class="d-flex mb-3">
      <b-input
        id="new-album-name"
        v-model="newAlbumName"
        placeholder="Album naam"
      ></b-input>
      <b-button
        class="ml-3"
        variant="primary"
        @click="createAlbum(newAlbumName)"
      >
        <span>Aanmaken</span>
      </b-button>
    </div>
    <table v-if="albums.length > 0" class="w-100 mb-2">
      <tr>
        <th>Album</th>
        <th>Aanpassen</th>
        <th>Verwijder</th>
      </tr>
      <tr v-for="(album, index) in albums" :key="album.id">
        <td>{{ album.name }}</td>
        <td>
          <b-button variant="primary" size="sm" @click="currentAlbum = index">
            <span>Aanpassen</span>
          </b-button>
        </td>
        <td>
          <b-button @click="deleteAlbum(index)" size="sm" variant="danger"
            >Verwijder</b-button
          >
        </td>
      </tr>
    </table>

    <strong v-if="currentAlbum != null"
      >Album: {{ albums[currentAlbum].name }}</strong
    >
    <imageUpload
      v-if="currentAlbum != null"
      @saveImages="addToImages"
      v-bind:index="currentAlbum"
      v-bind:album="albums[currentAlbum]"
      :key="currentAlbum"
    ></imageUpload>
    <hr />
    <b-container v-if="currentAlbum != null">
      <b-row :key="reload" align-v="center">
        <b-col
          class="img-col p-1"
          v-for="(image, index) in albums[currentAlbum].images"
          :key="image.url"
        >
          <b-overlay
            class="d-flex img-controls-out"
            :show="image.deleting"
            rounded="sm"
          >
            <div
              class="img-smooth"
              :style="{ backgroundImage: `url('${image.url}')` }"
            ></div>
            <b-button
              class="img-controls m-auto"
              @click="removeImage(image, currentAlbum, index)"
              variant="danger"
              >Verwijderen</b-button
            >
          </b-overlay>
        </b-col>
      </b-row>
    </b-container>
  </b-card>
</template>

<script>
import axios from "@/plugins/axios.js";
import imageUpload from "./imageUpload.vue";
import Vue from "@/main.js";

export default {
  name: "imageEditor",
  props: ["user", "branch"],
  components: { imageUpload },
  data() {
    return {
      returned: false,
      albums: [],
      currentAlbum: null,
      newAlbumName: null,
      reload: 0,
    };
  },
  created() {
    axios
      .post("/branch/albums/get", {
        branchName: this.branch.branchName,
      })
      .then((response) => {
        this.albums = response.data;
        this.returned = true;
      });
  },
  methods: {
    deleteAlbum(index) {
      axios
        .post("/branch/album/delete", {
          branchName: this.branch.branchName,
          albumId: this.albums[index].id,
        })
        .then(() => {
          if (index == this.currentAlbum) {
            this.currentAlbum = null;
          }
          this.albums.splice(index, 1);
        });
    },
    createAlbum(albumName) {
      axios
        .post("/branch/album/insert", {
          branchName: this.branch.branchName,
          albumName: albumName,
        })
        .then((response) => {
          response.data[0].images = [];
          this.albums.push(response.data[0]);
        });
    },
    addToImages(images, id) {
      for (let i = 0; i < images.length; i++) {
        this.albums[id].images.unshift({
          url: images[i].url,
          deleting: false,
        });
      }
    },
    removeImage(image, index, imageId) {
      this.albums[index].images[imageId].deleting = true;
      this.reload++;
      axios
        .post("/branch/photo/delete", {
          albumId: this.albums[index].id,
          image: image.url,
        })
        .then((response) => {
          this.uploading = false;
          if (response.status == 200) {
            this.albums[index].images = this.albums[index].images.filter(
              (i) => i.url != image.url
            );
          } else {
            this.$bvToast.toast("Unknown", Vue.toastObject("Error"));
          }
        })
        .catch((error) => {
          this.uploading = false;
          if (error.response.status === 401) {
            this.$bvToast.toast("Unauthorised", Vue.toastObject("Error"));
          } else {
            this.$bvToast.toast(error + "", Vue.toastObject("Error"));
          }
        });
    },
  },
};
</script>


<style>
.img-smooth {
  background-size: cover;
  background-repeat: no-repeat;
  background-position: 50% 50%;
  margin-left: 0.25em;
  margin-right: 0.25em;
  width: 100%;
  height: 200px;
  display: inline-block;
  border-radius: 6px !important;
}

.img-col {
  min-width: 25%;
  max-width: 50%;
}

@media only screen and (max-width: 750px) {
  .img-col {
    min-width: 50%;
  }
}

@media only screen and (max-width: 450px) {
  .img-col {
    min-width: 100%;
  }
}

.img-controls {
  display: none;
  text-align: center;
}

.img-controls-out:hover .img-controls {
  display: inline;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
.img-controls-out div {
  transition: 0.3s;
}

.img-controls-out:hover div {
  opacity: 0.8;
}
</style>

