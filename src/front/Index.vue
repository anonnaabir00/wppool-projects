<template>
    <div>
      <div class="flex justify-end">
        <!-- Category filter -->
        <Dropdown v-model="selectedCategory" :options="categories" optionLabel="name" placeholder="Project Category" @change="filterProjects" />
      </div>

      <ul v-if="loading" class="grid sm:grid-cols-1 md:grid-cols-3 gap-6">
        <li class="mt-6 border border-solid border-gray-100 shadow-lg">
          <Skeleton width="100%" height="16rem" />
        </li>
        <li class="mt-6 border border-solid border-gray-100 shadow-lg">
          <Skeleton width="100%" height="16rem" />
        </li>
        <li class="mt-6 border border-solid border-gray-100 shadow-lg">
          <Skeleton width="100%" height="16rem" />
        </li>
      </ul>

      <!-- Project list -->
      <ul class="grid sm:grid-cols-1 md:grid-cols-3 gap-6">
      <li class="mt-6 border border-solid border-gray-100 shadow-lg" v-for="project in filteredProjects" :key="project.title">
        <div @click="openModal(project.id)" class="cursor-pointer no-underline text-left w-full h-full p-0 border-gray-100">
          <div class="h-64 relative">
            <img :src="project.thumbnail" class="w-full h-full p-0 object-cover" alt="" />
            <div class="project-title absolute top-0 right-0 bottom-0 left-0 flex items-center justify-center text-white text-center bg-black bg-opacity-75 transition-opacity opacity-0">
              <p class="text-2xl font-light leading-relaxed" v-html="project.title"></p>
            </div>
          </div>
        </div>
      </li>
    </ul>
  
      <!-- Pagination -->
      <div class="mt-6 flex justify-center">
        <button @click="previousPage" :disabled="currentPage === 1" class="bg-black text-white pl-3 pr-3 p-2 m-4">Previous</button>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="bg-black text-white pl-3 pr-3 p-2 m-4">Next</button>
      </div>

        <Sidebar :visible.sync="displayModal" header="Header" position="full" showCloseIcon="true">
          <div v-if="singleloading">
            <div class="flex justify-center">
                <div class="sm:w-full md:w-3/6">
                  <div class="sm:block md:flex justify-between items-center mt-6 mb-6">
                    <Skeleton width="100%" height="2rem" />
                    <div class="sm:mt-8 md:mt-4 sm:mb-8 md:mb-0">
                      <Skeleton width="100%" height="2rem" />
                    </div>
                  </div>
                  <Skeleton width="100%" height="20rem" />
                </div>
              </div> 
          </div>

          <div v-if="selectedProject">
              <div class="flex justify-center">
                <div class="sm:w-full md:w-3/6">
                  <div class="sm:block md:flex justify-between items-center mt-6 mb-6">
                    <h2 class="text-3xl mt-3">{{ selectedProject.title }}</h2>
                    <div class="sm:mt-8 md:mt-4 sm:mb-8 md:mb-0">
                      <a :href="selectedProject.external_url" target="_blank"  class="bg-pink-500 p-3 text-white hover:text-white">View On Dribbble</a>
                    </div>
                  </div>
                  <img :src="selectedProject.thumbnail" class="w-full p-0 mb-5" alt="" />
                  <div class="flex justify-between mt-8 mb-8">
                    <p class="text-base font-bold">Project Cateogry: </p>
                    <ul class="flex">
                      <li v-for="category in selectedProject.categories">
                        <Tag :value="category" class="mr-2"></Tag>
                      </li>
                    </ul>
                  </div>
                  <h3 class="text-2xl mt-6 mb-6">Project Description</h3>
                  <div v-html="selectedProject.content" class="font-light leading-relaxed" ></div>
                  <h3 class="text-2xl mt-6 mb-6">Project Images</h3>
                  <Carousel :value="selectedProject.gallery">
                  <template #item="slotProps">
                    <img :src=slotProps.data class="cursor-pointer w-full h-96 object-contain" alt="" />
                </template>
              </Carousel>
                </div>
              </div>    
        </div>

        </Sidebar>

      <!--
      <div v-if="selectedProject">
        <Sidebar :visible.sync="displayModal" header="Header" position="full" showCloseIcon="true">
          <div class="flex justify-center">
            <div class="sm:w-full md:w-3/6">
              <div class="sm:block md:flex justify-between items-center mt-6 mb-6">
                <h2 class="text-3xl mt-3">{{ selectedProject.title }}</h2>
                <div class="sm:mt-8 md:mt-4 sm:mb-8 md:mb-0">
                  <a :href="selectedProject.external_url" target="_blank"  class="bg-pink-500 p-3 text-white hover:text-white">View On Dribbble</a>
                </div>
              </div>
              <img :src="selectedProject.thumbnail" class="w-full p-0 mb-5" alt="" />
              <div class="flex justify-between mt-8 mb-8">
                <p class="text-base font-bold">Project Cateogry: </p>
                <ul class="flex">
                  <li v-for="category in selectedProject.categories">
                    <Tag :value="category" class="mr-2"></Tag>
                  </li>
                </ul>
              </div>
              <h3 class="text-2xl mt-6 mb-6">Project Description</h3>
              <div v-html="selectedProject.content" class="font-light leading-relaxed" ></div>
              <h3 class="text-2xl mt-6 mb-6">Project Images</h3>
              <Carousel :value="selectedProject.gallery">
              <template #item="slotProps">
                <img :src=slotProps.data class="cursor-pointer w-full h-96 object-contain" alt="" />
            </template>
          </Carousel>
            </div>
          </div>
        </Sidebar>      
    </div>
    -->

      </div>
  </template>
  
  <script>
  import Vue from 'vue';
  import axios from 'axios';

  import Button from 'primevue/button';
  import Dialog from 'primevue/dialog';
  import Carousel from 'primevue/carousel';
  import Sidebar from 'primevue/sidebar';
  import Dropdown from 'primevue/dropdown';
  import MultiSelect from 'primevue/multiselect';
  import Tag from 'primevue/tag';
  import Skeleton from 'primevue/skeleton';
  
  Vue.component('Dialog', Dialog);
  Vue.component('Button', Button);
  Vue.component('Carousel', Carousel);
  Vue.component('Sidebar', Sidebar);
  Vue.component('Dropdown', Dropdown);
  Vue.component('MultiSelect', MultiSelect);
  Vue.component('Tag', Tag);
  Vue.component('Skeleton', Skeleton);

  export default {
    name: 'Index',
    data() {
      return {
        projects: [],
        categories: [],
        filteredProjects: [],
        selectedCategory: '',
        currentPage: 1,
        totalPages: 0,
        selectedProject: null,
        toggler: false,
        slide: 2,
        displayModal: false,
        loading: false,
        singleloading: false,
      };
    },
    methods: {
      loadProjects(page) {
        this.loading = true;

        const url = `/wp-json/wppool/v1/projects?page=${page}${this.selectedCategory ? '&category=' + this.selectedCategory : ''}`;
  
        axios
          .get(url)
          .then((response) => {
            this.projects = response.data.projects;
            this.totalPages = response.data.total_pages;
            this.filteredProjects = this.filterByCategory(this.projects);
            // console.log(response.data);
          })
          .catch((error) => {
            console.log(error);
          }).finally(() => {
            this.loading = false;
          });
      },
      loadCategories() {
        this.loading = true;

        axios
          .get('/wp-json/wppool/v1/categories')
          .then((response) => {
            // console.log(response.data);
            this.categories = response.data;
          })
          .catch((error) => {
            console.log(error);
          }).finally(() => {
            this.loading = false;
          });
      },
      filterProjects() {
        this.filteredProjects = this.filterByCategory(this.projects);
        this.currentPage = 1; // Reset the current page when filtering
      },
      filterByCategory(projects) {
        // console.log(this.selectedCategory)
        if (this.selectedCategory) {
          return projects.filter((project) => project.categories.includes(this.selectedCategory.slug));
        }
        return projects;
      },
      previousPage() {
        if (this.currentPage > 1) {
          this.currentPage--;
          this.loadProjects(this.currentPage);
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.currentPage++;
          this.loadProjects(this.currentPage);
        }
      },
      openModal(projectId) {
        this.selectedProject = null;
        this.singleloading = true;
        this.displayModal = true;
        axios
          .get(`/wp-json/wppool/v1/projects/${projectId}`)
          .then((response) => {
            this.selectedProject = response.data;
            // console.log(this.selectedProject);
          })
          .catch((error) => {
            console.log(error);
          }).finally(() => {
            this.singleloading = false;
          });
      },
      closeModal() {
        this.selectedProject = null;
        this.displayModal = false;
      },
      OpenLightbox(number) {
        this.slide = number;
				this.toggler = !this.toggler;
    },
    },
    mounted() {
      this.loadProjects(this.currentPage);
      this.loadCategories();
      // this.imageGallery();
    },
  };
  </script>
  