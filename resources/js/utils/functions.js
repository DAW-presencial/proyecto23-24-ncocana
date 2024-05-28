import moment from 'moment';
import { ref } from 'vue';

export const getParamsBookmark = async (type, bookmark_data) => {
    // console.log(type);
    // console.log(bookmark_data);
    if (type === "App\\Models\\Movie" || type === "Movie") {

        return {
            actors: bookmark_data.actors,
            currently_at: bookmark_data.currently_at,
            director: bookmark_data.director,
            release_date: bookmark_data.release_date,
        };
    }
    if (type === "App\\Models\\Fanfic" || type === "Fanfic") {

        return {
            author: bookmark_data.author,
            fandom: bookmark_data.fandom,
            language: bookmark_data.language,
            words: bookmark_data.words, // Asegúrate de que sea un entero
            read_chapters: bookmark_data.read_chapters, // Asegúrate de que sea un entero
            total_chapters: bookmark_data.total_chapters,
            relationships: bookmark_data.relationships,
        };
    }
    if (type === "App\\Models\\Book" || type === "Book") {

        return {
            author: bookmark_data.author,
            language: bookmark_data.language,
            read_pages: bookmark_data.read_pages,
            total_pages: bookmark_data.total_pages,
        };
    }
    if (type === "App\\Models\\Series" || type === "Series") {

        return {
            actors: bookmark_data.actors,
            currently_at: bookmark_data.currently_at,
            num_episodes: bookmark_data.num_episodes,
            num_seasons: bookmark_data.num_seasons,
        };
    }
};

export const nextPage = (currentPage, lastPage) => {
    if (currentPage < lastPage) {
        currentPage++;
        getBookmarks();
    }
};

export const prevPage = (currentPage) => {
    if (currentPage > 1) {
        currentPage--;
        getBookmarks();
    }
};

export const formatDate = (date) => {
    return moment(date).format('YYYY/MM/DD');
};


// getBookmarks
// export const sort = ref(null);
// export const tags = ref(null);
// export const currentPage = ref(1);
// export const lastPage = ref(null);
// export const bookmarks = ref([]);

// export const getBookmarks = () => {
//     axios.get(`/bookmarks`, {
//         params: {
//             'sort': sort.value,
//             'tags': tags.value,
//             'page[size]': 2,
//             'page[number]': currentPage.value
//         }
//     })
//         .then(response => {
//             const res = response.data;
//             const data = res.data;

//             // Pagination
//             currentPage.value = res.meta.current_page;
//             lastPage.value = res.meta.last_page;

//             bookmarks.value = [];
//             for (let i = 0; i < data.length; i++) {
//                 let json = data[i].attributes.bookmarkable;
//                 json.tipo = data[i].attributes.bookmarkable_type;
//                 json.id = data[i].id;
//                 json.title = data[i].attributes.title;
//                 json.synopsis = data[i].attributes.synopsis;
//                 json.notes = data[i].attributes.notes;

//                 json.tags = [];
//                 for (let x = 0; x < data[i].attributes.tags.length; x++) {
//                     json.tags.push(data[i].attributes.tags[x].name);
//                 }
//                 json.tags = json.tags.join(',');

//                 bookmarks.value.push(json);
//             }
//         })
//         .catch(error => console.log('Ha ocurrido un error: ' + error));
// };
