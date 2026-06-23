<template>
    <div>
        <div class="buttons column">
            <a class="button is-info" @click="importMedia" :class="{'is-loading': isLoading}">
                <span class="icon is-small">
                    <i class="fa fa-check"></i>
                </span>
                Import Movie
            </a>
        </div>
    </div>
</template>
<script>

import axios from "axios";

export default {
  name: "import-button",
  props: ["currentmovies", "api", 'option'],
  data: function() {
    return {
      movieDetails: {},
      movieCredits: {},
      postID: '',
      mediaID: '',
      movieVideos: [],
      movieGenres: '',
      movieCast: '',
      movieCollection: '',
      bannerID: '',
      bannerURL: '',
      isLoading: false,
    };
  },

  computed: {
    /**
     *  Get neccessary movie fields
     */
    initialMovieData: function() {
      if (this.option == "movie") {
        let movie = {
          title: this.movieDetails.title,
          status: 'publish'
        };
        if (importMovieLocalize['import_tagline'] == 'enable') {
          movie.tagline = this.movieDetails.tagline;
        }
        if (importMovieLocalize['import_overview'] == 'enable') {
          movie.overview = this.movieDetails.overview;
        }
        if (importMovieLocalize['import_release_date'] == 'enable') {
          movie.release_date = this.movieDetails.release_date;
        }
        if (importMovieLocalize['import_runtime'] == 'enable' ) {
          movie.runtime = this.movieDetails.runtime + "m";
        }
        if (importMovieLocalize['import_language'] == 'enable' ) {
          movie.languages = this.movieDetails.spoken_languages.map(language => language.name).join(", ");
        }
        if (importMovieLocalize['import_country'] == 'enable' ) {
          movie.country = this.movieDetails.production_countries.map(country => country.name).join(", ");
        }
        if (importMovieLocalize['import_production'] == 'enable' ) {
          movie.production = this.movieDetails.production_companies.map(company => company.name).join(", ");
        }
        if (importMovieLocalize['import_director'] == 'enable') {
          let director = [];
          this.movieCredits.crew.forEach(function(crew) {
            if (crew.department == 'Directing' && crew.job == "Director") {
              director.push(crew.name);
            }
          });
          movie.directors = director.join(", ");
        }
        if (importMovieLocalize['import_writer'] == 'enable') {
          let writer = [];
          this.movieCredits.crew.forEach(function(crew) {
            if (crew.department == 'Writing' && crew.job == 'Screenplay') {
              writer.push(crew.name);
            }
          });
          movie.writers = writer.join(", ");
        }

        return movie;
      }

      let tv = {
        title: this.movieDetails.name,
        status: 'publish'
      }
      if (importMovieLocalize['import_tv_overview'] == 'enable') {
        tv.overview = this.movieDetails.overview;
      }
      if (importMovieLocalize['import_first_air_date'] == 'enable') {
        tv['first_air_date'] = this.movieDetails['first_air_date'];
      }
      if (importMovieLocalize['import_episode_runtime'] == 'enable') {
        tv['episode_runtime'] = this.movieDetails['episode_run_time'][0] + 'm';
      }
      if (importMovieLocalize['import_tv_creator'] == 'enable') {
        tv.creators = this.movieDetails['created_by'].map(creator => creator.name).join(', ');
      }

      if (importMovieLocalize['import_tv_country'] == 'enable' ) {
        tv.country = this.movieDetails.origin_country.join(", ");
      }
      if (importMovieLocalize['import_tv_production'] == 'enable' ) {
        tv.production = this.movieDetails.production_companies.map(company => company.name).join(", ");
      }
      if (importMovieLocalize['import_tv_language'] == 'enable' ) {
        tv.languages = this.movieDetails.original_language;
      }
      if (importMovieLocalize['import_tv_season'] == 'enable' ) {
        let seasons = [];
        this.movieDetails.seasons.forEach(season => {
          let tempSeason = {
            'air_date': season['air_date'],
            'overview': season['overview'],
            'season_number': season['season_number'],
            'episode_count': season['episode_count'],
            'poster_path': 'https://image.tmdb.org/t/p/w154' + season['poster_path'],
          };
          seasons.push(tempSeason);
        });
        tv.seasons = seasons;
      }

      return tv;
    }
  },

  watch: {
    currentmovies: {
      handler: function(newMovie, oldMovie) {
        this.getMovieDetails();
        this.getMovieCredits();
      },
      deep: true
    }
  },

  mounted() {
    this.getMovieDetails();
    this.getMovieCredits();
  },

  methods: {
    /**
     * Get selected movie details
     */
    getMovieDetails: function() {
      let id = this.currentmovies.id;
      let query = this.option === 'movie' ?
        `https://api.themoviedb.org/3/movie/${id}`
        : `https://api.themoviedb.org/3/tv/${id}`;
      axios
        .get(query, {
          params: {
            api_key: this.api,
            language: importMovieLocalize.language
          }
        })
        .then(response => {
          this.movieDetails = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    /**
     * Get selected movie credits
     */
    getMovieCredits: function() {
      let id = this.currentmovies.id;
      let query = this.option === 'movie'
        ? `https://api.themoviedb.org/3/movie/${id}/credits`
        : `https://api.themoviedb.org/3/tv/${id}/credits`;
      axios
      .get(query, {
        params: {
          api_key: this.api,
          language: importMovieLocalize.language
        }
      })
      .then(response => {
        this.movieCredits = response.data;
      })
      .catch(error => {
        console.log(error);
      });
    },

    /**
     * Create new movie post
     */
    createMovies: function() {
      let url = this.option === 'movie'
        ? importMovieLocalize.rest_url + "wp/v2/ht_movie"
        : importMovieLocalize.rest_url + "wp/v2/ht_show";
      return  axios({
          method: "post",
          url: url,
          data: this.initialMovieData,
          headers: {
            "X-WP-Nonce": importMovieLocalize.nonce
          }
        })
          .then(response => {
            this.postID = response.data.id;
          })
          .catch(error => {
            console.log(error);
          });
    },

    /**
     * Get selected movie videos
     */
    getMovieVideos: function() {
      if (this.option === 'movie') {
        if (importMovieLocalize['import_trailer'] == 'disable') return;
        let id = this.currentmovies.id;
        axios
        .get(`https://api.themoviedb.org/3/movie/${id}/videos`, {
          params: {
            api_key: this.api,
            language: importMovieLocalize.language
          }
        })
        .then(response => {
          this.movieVideos = response.data.results.map(result => result.key);
        })
        .catch(error => {
          console.log(error);
        });
      } else {
        if (importMovieLocalize['import_tv_trailer'] == 'disable') return;
        let id = this.currentmovies.id;
        axios
        .get(`https://api.themoviedb.org/3/tv/${id}/videos`, {
          params: {
            api_key: this.api,
            language: importMovieLocalize.language
          }
        })
        .then(response => {
          this.movieVideos = response.data.results.map(result => result.key);
        })
        .catch(error => {
          console.log(error);
        });
      }
    },

    /**
     * Get selected movie genres
     */
    getMovieGenres: function() {
      if (this.option == 'movie') {
        if (importMovieLocalize['import_genre'] == 'disable') return;
        let data = new FormData();
        data.append('genres', this.movieDetails.genres.map(genre => genre.name));
        return axios({
            method: 'post',
            url: importMovieLocalize.ajax_url,
            params: {
              action: 'ht_movie_add_genres'
            },
            data: data,
            headers: {
              "X-WP-Nonce": importMovieLocalize.nonce
            }
          }).then(response => {
            this.movieGenres = response.data.result;
          }).catch(error => {
            console.log(error);
          });
      } else {
        if (importMovieLocalize['import_tv_genre'] == 'disable') return;
        let data = new FormData();
        data.append('genres', this.movieDetails.genres.map(genre => genre.name));
        return axios({
            method: 'post',
            url: importMovieLocalize.ajax_url,
            params: {
              action: 'ht_movie_add_genres'
            },
            data: data,
            headers: {
              "X-WP-Nonce": importMovieLocalize.nonce
            }
          }).then(response => {
            this.movieGenres = response.data.result;
          }).catch(error => {
            console.log(error);
          });
      }
    },

    /**
     * Get selected movie cast
     */
    getMovieCast: function() {
      if (this.option == 'movie') {
        if (importMovieLocalize['import_cast'] == 'disable') return;
        let data = new FormData(),
          casts = this.movieCredits.cast.slice(0, 10);
        data.append('casts', casts.map(cast => cast.name));
        data.append('avatar_url', casts.map(cast => cast['profile_path']));
        data.append('gender', casts.map(cast => cast.gender));
        if ( importMovieLocalize['import_castmoreinfo'] == 'enable' ) {
          data.append('person_id', casts.map(cast => cast.id) );
          data.append('api_key', this.api);
        }
        return axios ({
          method: 'post',
          url: importMovieLocalize.ajax_url,
          params: {
            action: 'ht_movie_add_cast'
          },
          data: data,
          headers: {
              "X-WP-Nonce": importMovieLocalize.nonce
          }
        }).then(response => {
            this.movieCast = response.data.result;
          }).catch(error => {
            alert('Error! . Please try again later');
            console.log(error);
          });
      } else {
        if (importMovieLocalize['import_tv_cast'] == 'disable') return;
        let data = new FormData(),
          casts = this.movieCredits.cast.slice(0, 10);
        data.append('casts', casts.map(cast => cast.name));
        data.append('avatar_url', casts.map(cast => cast['profile_path']));
        return axios ({
          method: 'post',
          url: importMovieLocalize.ajax_url,
          params: {
            action: 'ht_movie_add_cast'
          },
          data: data,
          headers: {
              "X-WP-Nonce": importMovieLocalize.nonce
          }
        }).then(response => {
            this.movieCast = response.data.result;
          }).catch(error => {
            console.log(error);
          });
      }
    },

    /**
     * Get selected movie collection
     */
    getMovieCollection: function() {
      if (!this.movieDetails.belongs_to_collection || (importMovieLocalize['import_collection'] == 'disable')) return;
      return axios({
          method: 'get',
          url: importMovieLocalize.ajax_url,
          params: {
            action: 'ht_movie_add_collection',
            collection: this.movieDetails.belongs_to_collection.name
          },
          headers: {
            "X-WP-Nonce": importMovieLocalize.nonce
          }
        }).then(response => {
          console.log(response.data);
          this.movieCollection = response.data;
        }).catch(error => {
          console.log(error);
        });
    },

    /**
     * Send movie poster url to server
     */
    sendPosterSrc: function() {
      if (this.option == 'movie') {
        if (importMovieLocalize['import_poster'] == 'disable') return;
        return axios({
            method: 'get',
            url: importMovieLocalize.ajax_url,
            params: {
              action: 'ht_movie_add_poster_src',
              img: 'https://image.tmdb.org/t/p/w780/' + this.movieDetails.poster_path,
              language: importMovieLocalize.language
            },
            headers: {
              "X-WP-Nonce": importMovieLocalize.nonce
            }
          }).then(response => {
            this.mediaID = response.data.id;
          }).catch(error => {
            console.log(error);
          });
      } else {
        if (importMovieLocalize['import_tv_poster'] == 'disable') return;
        return axios({
            method: 'get',
            url: importMovieLocalize.ajax_url,
            params: {
              action: 'ht_movie_add_poster_src',
              img: 'https://image.tmdb.org/t/p/w780/' + this.movieDetails.poster_path,
              language: importMovieLocalize.language
            },
            headers: {
              "X-WP-Nonce": importMovieLocalize.nonce
            }
          }).then(response => {
            this.mediaID = response.data.id;
          }).catch(error => {
            console.log(error);
          });
      }
    },

    /**
     * Send movie banner url to server
     */
     sendBannerSrc: function() {
      if (this.option == 'movie') {
        if (importMovieLocalize['import_banner'] == 'disable') return;
        return axios({
            method: 'get',
            url: importMovieLocalize.ajax_url,
            params: {
              action: 'ht_movie_add_banner_src',
              img: 'https://image.tmdb.org/t/p/w1280/' + this.movieDetails.backdrop_path,
              language: importMovieLocalize.language
            },
            headers: {
              "X-WP-Nonce": importMovieLocalize.nonce
            }
          }).then(response => {
            this.bannerID = response.data.id;
            this.bannerURL = response.data.url;
          }).catch(error => {
            console.log(error);
          });
      } else {
        if (importMovieLocalize['import_tv_banner'] == 'disable') return;
        return axios({
            method: 'get',
            url: importMovieLocalize.ajax_url,
            params: {
              action: 'ht_movie_add_banner_src',
              img: 'https://image.tmdb.org/t/p/w1280/' + this.movieDetails.backdrop_path,
              language: importMovieLocalize.language
            },
            headers: {
              "X-WP-Nonce": importMovieLocalize.nonce
            }
          }).then(response => {
            this.bannerID = response.data.id;
            this.bannerURL = response.data.url;
          }).catch(error => {
            console.log(error);
          });
      }
    },

    /**
     * Update movie post
     */
    updateMovies: function() {
      let data = {};
      let url = importMovieLocalize.rest_url + `wp/v2/ht_show/${this.postID}`;
      if (this.option == 'movie') {
        if (this.movieCollection) {
          data['mv_collection'] = this.movieCollection;
        }
        url = importMovieLocalize.rest_url + `wp/v2/ht_movie/${this.postID}`;
      }
      if (this.mediaID) {
        data['featured_media'] = this.mediaID;
      }
      if (this.bannerID) {
        let banner = {
          'attachment_id': this.bannerID,
          'url': this.bannerURL
        };
        data.banner = banner;
      }
      if (this.movieGenres) {
        data['mv_genre'] = this.movieGenres;
      }
      if (this.movieCast) {
        data['mv_actor'] = this.movieCast;
      }
      if (this.movieVideos) {
        data.video = this.movieVideos;
      }
      axios({
        method: 'post',
        url: url,
        data: data,
        headers: {
          "X-WP-Nonce": importMovieLocalize.nonce
        }
      })
        .then(response => {
          this.alertDisplay();
          this.clearResults();
        }).catch(error => {
          console.log(error);
        });
    },

    /**
     * Finalize creating movie post process
     */
    importMovies: function() {
      this.isLoading = !this.isLoading;

      axios.all([
        this.createMovies(),
        this.sendPosterSrc(),
        this.sendBannerSrc(),
        this.getMovieCollection(),
        this.getMovieGenres(),
        this.getMovieCast(),
        this.getMovieVideos()])
      .then(axios.spread(() => {
        this.updateMovies();
      }));
    },

    importTV: function() {
      this.isLoading = !this.isLoading;

      axios.all([
        this.createMovies(),
        this.sendPosterSrc(),
        this.sendBannerSrc(),
        this.getMovieGenres(),
        this.getMovieCast(),
        this.getMovieVideos()])
      .then(axios.spread(() => {
        this.updateMovies();
      }));
    },

    importMedia: function() {
      if (this.option == 'movie') {
        this.importMovies();
      } else {
        this.importTV();
      }
    },

    /**
     * Clear search result after importing movie
     */
    clearResults: function() {
      this.$emit("clear-movies", []);
      this.$emit('clear-selected-movie', '');
    },

    /**
     * Display success message
     */
    alertDisplay: function() {
      if (this.option == 'movie') {
        this.$swal({
          title: "Success",
          text: "Movie imported successfully!",
          type: "success",
          showCloseButton: true
        });
      } else {
        this.$swal({
          title: "Success",
          text: "TV Show imported successfully!",
          type: "success",
          showCloseButton: true
        });
      }
    }
  }
};
</script>

