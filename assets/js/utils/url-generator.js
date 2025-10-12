import axios from "axios";
import {apiConfig} from "./settings";

export function getUrlViewProduct(viewUrl, productId){
    return (
        window.location.protocol +
            '//' +
            window.location.host +
            viewUrl +
            '/' +
            productId
    );
}

export function getUrlProductsByCategory(defaultUrl, categoryId, page, countLimit){
    return (defaultUrl
        + "?category=/api/categories/"
        + categoryId
        + "&isPublish=true"
        + "&page="
        + page
        + "&itemsPerPage="
        + countLimit
    );
}

export function concatUrlByParams(...params){
    return params.join("/");
}
