<template>
    <div>
      <div>
        <!-- Category filter -->
        <label for="category">Category:</label>
        <select id="category" v-model="selectedCategory" @change="filterProjects">
          <option value="">All</option>
          <option v-for="category in categories" :value="category.slug" :key="category.id">{{ category.name }}</option>
        </select>
      </div>

      <!-- Project list -->
      <ul class="grid grid-cols-3 gap-6">
      <li class="mt-6 border border-solid border-gray-100 shadow-lg" v-for="project in filteredProjects" :key="project.title">
        <div @click="openModal(project.id)" class="cursor-pointer no-underline text-left w-full h-full p-0 border-gray-100">
          <div class="h-64 relative">
            <img :src="project.thumbnail" class="w-full h-full p-0 object-cover" alt="" />
            <div class="project-title absolute top-0 right-0 bottom-0 left-0 flex items-center justify-center text-white text-center bg-black bg-opacity-75 transition-opacity opacity-0">
              <p class="text-xl font-bold">{{ project.title }}</p>
            </div>
          </div>
        </div>
      </li>
    </ul>
  
      <!-- Pagination -->
      <div class="mt-8">
        <button @click="previousPage" :disabled="currentPage === 1">Previous</button>
        <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
      </div>
  
      <!-- Modal -->
      <div v-if="selectedProject" class="fixed inset-0 flex items-center justify-center z-50 content-center space-x-4 overflow-auto">
        <div class="fixed inset-0 bg-black opacity-75"></div>
        <div class="sm:block md:flex relative bg-white rounded-lg shadow-lg max-w-4xl mx-auto">
            <!-- Modal content -->
            <FsLightbox :toggler="toggler" :sources="selectedProject.gallery" :slide="slide" />
            <div class="bg-gray-100">
              <div class="h-64">
              <img :src="selectedProject.thumbnail" class="w-full h-full p-0 object-cover" alt="" />
            </div>
                <ul class="grid grid-cols-2 gap-2 my-0 p-0">
                    <li class="" v-for="gallery in selectedProject.gallery">
                    <img @click=OpenLightbox(number) :src=gallery class="cursor-pointer w-full sm:h-48 md:h-72 object-cover" alt="" />
                </li>
                </ul>
            </div>
            <div class="pt-6 pl-8 pr-24">
                <h3 class="text-2xl font-semibold mb-4">{{ selectedProject.title }}</h3>
                <p class="text-base leading-relaxed mb-8">{{ selectedProject.custom_field.description }}</p>
            <div class="mt-4 flex justify-end">
            <button @click="closeModal" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Close</button>
            </div>
            </div>
        </div>
        </div>
        <!-- Modal End -->

      </div>
  </template>
  
  <script>
  import Vue from 'vue';
  import axios from 'axios';
  import 'animate.css';
  import 'flowbite';
  import FsLightbox from "fslightbox-vue";

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
        slide: 2
      };
    },
    components: { FsLightbox },
    methods: {
      loadProjects(page) {
        const url = `/wp-json/wppool/v1/projects?page=${page}${this.selectedCategory ? '&category=' + this.selectedCategory : ''}`;
  
        axios
          .get(url)
          .then((response) => {
            this.projects = response.data.projects;
            this.totalPages = response.data.total_pages;
            this.filteredProjects = this.filterByCategory(this.projects);
            console.log(response.data);
          })
          .catch((error) => {
            console.log(error);
          });
      },
      loadCategories() {
        axios
          .get('/wp-json/wppool/v1/categories')
          .then((response) => {
            console.log(response.data);
            this.categories = response.data;
          })
          .catch((error) => {
            console.log(error);
          });
      },
      filterProjects() {
        this.filteredProjects = this.filterByCategory(this.projects);
        this.currentPage = 1; // Reset the current page when filtering
      },
      filterByCategory(projects) {
        if (this.selectedCategory) {
          return projects.filter((project) => project.categories.includes(this.selectedCategory));
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
        axios
          .get(`/wp-json/wppool/v1/projects/${projectId}`)
          .then((response) => {
            this.selectedProject = response.data;
            console.log(this.selectedProject);
          })
          .catch((error) => {
            console.log(error);
          });
      },
      closeModal() {
        this.selectedProject = null;
      },
      OpenLightbox(number) {
        this.slide = number;
				this.toggler = !this.toggler;
    }
    },
    mounted() {
      this.loadProjects(this.currentPage);
      this.loadCategories();
      // this.imageGallery();
    },
  };
  </script>
  