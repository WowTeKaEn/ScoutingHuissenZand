<template>
  <b-card>
    <div class="w-100 d-flex" v-if="!returned">
      <b-spinner class="m-auto" variant="primary" label="Spinning"></b-spinner>
    </div>
    <div v-else class="account-table">
      <table class="w-100 mb-2">
        <tr>
          <th>Email</th>
          <th>Gevalideerd</th>
          <th>Admin</th>
          <th>Verwijder</th>
        </tr>
        <tr v-for="user in users" :key="user.email">
          <td>{{ user.email }}</td>
          <td>
            <b-form-checkbox
              switch
              v-model="user.activated"
              value="1"
              unchecked-value="0"
              @input="updateActivated(user)"
            ></b-form-checkbox>
          </td>
          <td>
            <b-form-checkbox
              switch
              v-model="user.admin"
              value="1"
              unchecked-value="0"
              @input="updateAdmin(user)"
            ></b-form-checkbox>
          </td>
          <td>
            <b-button
              @click="deleteUser(user)"
              v-if="user.deletable == 1"
              class="w-100"
              size="sm"
              variant="danger"
              >Verwijder</b-button
            >
            <div
              v-else
              v-b-popover.hover="'Dit account is gekoppeld aan een speltak'"
            >
              <b-button disabled class="w-100" size="sm" variant="danger"
                >Verwijder</b-button
              >
            </div>
          </td>
        </tr>
      </table>
    </div>
  </b-card>
</template>

<style>
/* Let's get this party started */
.account-table::-webkit-scrollbar {
  height: 6px;
}

/* Track */
.account-table::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  -webkit-border-radius: 10px;
  border-radius: 10px;
}

/* Handle */
.account-table::-webkit-scrollbar-thumb {
  -webkit-border-radius: 10px;
  border-radius: 10px;
  background-color: darkgrey;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
}
.account-table {
  overflow-x: auto;
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

<script>
import axios from "@/plugins/axios.js";
import VueMixin from "@/main.js";

export default {
  name: "accountManager",
  data() {
    return {
      users: [],
      returned: false,
    };
  },
  methods: {
    deleteUser(user) {
      axios
        .post("/user/delete", {
          email: user.email,
        })
        .then((response) => {
          VueMixin.throwResponse(response, null, () => {
            this.users = this.users.filter((u) => u.email !== user.email);
          });
        })
        .catch((error) => {
          VueMixin.throwError(error);
        });
    },
    updateActivated(user) {
      axios
        .post("/user/validate", {
          email: user.email,
        })
        .then((response) => {
          VueMixin.throwResponse(response, () => {
            user.validated != user.validated;
          });
        })
        .catch((error) => {
          VueMixin.throwError(error, () => {
            user.validated != user.validated;
          });
        });
    },
    updateAdmin(user) {
      axios
        .post("/user/promote", {
          email: user.email,
        })
        .then((response) => {
          VueMixin.throwResponse(response, () => {
            user.admin != user.admin;
          });
        })
        .catch((error) => {
          VueMixin.throwError(error, () => {
            user.admin != user.admin;
          });
        });
    },
  },
  created() {
    axios
      .get("/user/get/all")
      .then((response) => {
        VueMixin.throwResponse(
          response,
          () => (this.returned = true),
          () => {
            this.users = response.data;
            this.returned = true;
          }
        );
      })
      .catch((error) => {
        VueMixin.throwError(error, () => (this.returned = true));
      });
  },
};
</script>