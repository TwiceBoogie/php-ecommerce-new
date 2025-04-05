import path from "path";
import { CleanWebpackPlugin } from "clean-webpack-plugin";

export default {
  entry: {
    main: "./public/assets/js/main.js",
    vendor: ["jquery", "jquery-validation"],
  },
  output: {
    filename: "[name].bundle.js",
    path: path.resolve(process.cwd(), "public/dist"),
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
