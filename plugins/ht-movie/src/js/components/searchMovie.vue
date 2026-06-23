<template>
    <div>
        <div class="field column is-12">
            <label class="label">Search Movie / TV Show by Title</label>
            <div class="columns is-gapless">
                <div class="column is-2">
                    <multiselect
                        v-model="selectedOption"
                        label="name"
                        track-by="value"
                        placeholder="Select search option"
                        :options="searchOptions"
                        :searchable="false"
                        :show-labels="false"
                        :allow-empty="false"
                        @input="onChange"
                    >
                    </multiselect>
                </div>
                <div class="column is-10">
                    <template v-if="!selectedOption">
                        <multiselect v-model="selectedMovies" id="ajax"
                            label="name"
                            track-by="id"
                            placeholder="Type to search"
                            open-direction="bottom"
                            :options="movies"
                            :searchable="true"
                            :loading="(!selectedMovies) ? false : isLoading"
                            :internal-search="false"
                            :options-limit="300"
                            :limit="113"
                            :limit-text="limitText"
                            :max-height="600"
                            :show-no-results="false"
                            :hide-selected="true"
                            @search-change="asyncFind">
                            <template slot="clear" slot-scope="props">
                            <div class="multiselect__clear" v-if="selectedMovies" @mousedown.prevent.stop="clearAll(props.search)"></div>
                            </template><span slot="noResult">Oops! No movie found.</span>
                        </multiselect>
                    </template>
                    <template v-else>
                        <multiselect v-model="selectedMovies" id="ajax"
                            :custom-label="(selectedOption.value === 'movie') ? customMovieLabel : customShowLabel"
                            track-by="id"
                            placeholder="Type to search"
                            open-direction="bottom"
                            :options="movies"
                            :searchable="true"
                            :loading="(!selectedMovies) ? false : isLoading"
                            :internal-search="false"
                            :options-limit="300"
                            :limit="113"
                            :limit-text="limitText"
                            :max-height="600"
                            :show-no-results="false"
                            :hide-selected="true"
                            @search-change="asyncFind">
                            <template slot="clear" slot-scope="props">
                            <div class="multiselect__clear" v-if="selectedMovies" @mousedown.prevent.stop="clearAll(props.search)"></div>
                            </template><span slot="noResult">Oops! No movie found.</span>
                        </multiselect>
                    </template>
                </div>
            </div>

            <table class="table is-fullwidth" v-if="selectedMovies">
                <thead>
                    <tr>
                        <template v-if="selectedOption.value === 'movie'">
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Overview</th>
                            <th>Release Date</th>
                        </template>
                        <template v-else>
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Name</th>
                            <th>Overview</th>
                            <th>First Air Date</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <template v-if="selectedOption.value === 'movie'">
                            <th>{{ selectedMovies.id }}</th>
                            <td><img class="avatar" :src="'https://image.tmdb.org/t/p/w185/' + selectedMovies.poster_path" alt="Avatar"></td>
                            <td>{{ selectedMovies.title }}</td>
                            <td class="overview">{{ selectedMovies.overview }}</td>
                            <td>{{ selectedMovies.release_date }}</td>
                        </template>
                        <template v-else>
                            <th>{{ selectedMovies.id }}</th>
                            <td><img class="avatar" :src="'https://image.tmdb.org/t/p/w185/' + selectedMovies.poster_path" alt="Avatar"></td>
                            <td>{{ selectedMovies.name }}</td>
                            <td class="overview">{{ selectedMovies.overview }}</td>
                            <td>{{ selectedMovies.first_air_date }}</td>
                        </template>
                    </tr>
                </tbody>
                <tfoot><import-button :currentmovies="selectedMovies" @clear-movies="movies = $event" @clear-selected-movie="selectedMovies = $event" :api="apiKey" :option="selectedOption.value"></import-button></tfoot>
            </table>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import import_button from "./import_button.vue";

export default {
  name: "searchmovie",
  components: {
    "import-button": import_button
  },
  data() {
    return {
      selectedMovies: null,
      apiKey: importMovieLocalize['api_key'],
      movies: [],
      isLoading: false,
      searchOptions: [
        {
          name: 'Movie',
          value: 'movie'
        },
        {
          name: 'TV Show',
          value: 'tv'
        }
      ],
      selectedOption: null,
      getAllmovieURL: null,
      labelAttr: 'movie'
    };
  },
  watch: {
    selectedOption: function(newOption, oldOption) {
      this.getAllmovieURL = `https://api.themoviedb.org/3/search/${this.selectedOption.value}?`;
    }
  },
  methods: {
    limitText(count) {
      return `and ${count} other movies`;
    },
    asyncFind(query) {
      if (importMovieLocalize['api_key']) {
        if (!this.selectedOption) {
          this.$swal({
            text: "Oops. Please select search option first!",
            type: "error",
            showCloseButton: true
          });
        } else {
          if (query != "") {
            this.isLoading = true;
            axios
              .get(this.getAllmovieURL, {
                params: {
                  api_key: this.apiKey,
                  query: query,
                  language: importMovieLocalize.language
                }
              })
              .then(response => {
                // JSON responses are automatically parsed.
                this.movies = response.data["results"];
              })
              .catch(e => {
                console.log(444);
                this.errors.push(e);
              });
          } else {
            this.isLoading = false;
          }
        }
      } else {
        this.$swal({
          title: "Oops. API Key is missing!",
          html: 'Enter an API key in plugin settings before searching for movies. Or register for an API key <a href="https://developers.themoviedb.org/3/getting-started/introduction">here</a>.',
          type: "error",
          showCloseButton: true
        });
      }
    },
    clearMovies() {
      this.selectedMovies = '';
    },
    onChange(option) {
      this.selectedMovies = '';
      this.movies = [];
    },
    customMovieLabel({ title }) {
      return `${title}`;
    },
    customShowLabel({ name }) {
      return `${name}`;
    }
  }
};
</script>
<style>
.multiselect__input,
.multiselect__single {
  border: none !important;
  box-shadow: none !important;
}
.avatar {
  max-width: 50px;
}

.overview {
  width: 50%;
}

</style>

