<template>
  <div id="app">
    <v-app id="inspire">
      <v-app-bar
        app
        color="white"
        flat
      >
        <v-tabs
          centered
          class="ml-n9"
          color="grey darken-1"
        >
          <v-tab
            v-for="link in links"
            :key="link.path"
            :to="link.path"
          >
            {{ link.value }}
          </v-tab>
        </v-tabs>
      </v-app-bar>

      <v-main class="grey lighten-3">
        <v-container>
          <v-row align="center"
                 justify="center">
            <v-col
              cols="12"
              sm="8"
              center
            >
              <v-sheet
                min-height="70vh"
                rounded="lg"
              >
                <router-view/>
              </v-sheet>
            </v-col>
          </v-row>
        </v-container>
      </v-main>
    </v-app>
    <loading/>
  </div>
</template>

<script>
import Loading from "./components/Loading";

export default {
  name: 'App',
  data: () => ({
    links: [
      {path: 'home', value: 'Kezdőlap'},
      {path: 'edit', value: 'Módosítás'},
    ],
  }),
  components: {
    Loading
  }
}
</script>
