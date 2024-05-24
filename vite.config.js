import path from "path";
import { defineConfig, splitVendorChunkPlugin } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.scss",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
        splitVendorChunkPlugin(),
    ],
    resolve: {
        alias: {
            "@css": path.resolve(__dirname, "resources/css"),
            "@js": path.resolve(__dirname, "resources/js"),
        },
    },
    build: {
        minify: true,
    },
});
