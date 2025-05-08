import path from "path";
import { fileURLToPath } from "url";
import { CleanWebpackPlugin } from "clean-webpack-plugin";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default {
  entry: {
    main: "./public/assets/js/main.js",
    vendor: ["jquery", "jquery-validation"],
  },
  output: {
    filename: "[name].bundle.js",
    path: path.resolve(process.cwd(), "public/dist"),
  },
  resolve: {
    alias: {
      "@utils": path.resolve(__dirname, "public/assets/js/utils"),
      "@api": path.resolve(__dirname, "public/assets/js/api"),
      "@modules": path.resolve(__dirname, "public/assets/js/modules"),
    },
  },
  mode: "development",
  devtool: "source-map",
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
    ],
  },
  plugins: [new CleanWebpackPlugin()],
};
