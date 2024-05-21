export const getParamsBookmark = async (type, bookmark_data) => {
    console.log(type);
    console.log(bookmark_data);
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
