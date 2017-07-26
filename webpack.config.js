const path = require("path");
const CleanWebpackPlugin = require("clean-webpack-plugin");
const CopyWebpackPlugin = require("copy-webpack-plugin");
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");

const paths = {
  src: "./src",
  dest: "./dist/wp-content/themes/custom",
  public: "/wp-content/themes/custom"
};

const moduleRules = {
  script: {
    test: /\.js$/i,
    exclude: /node_modules/,
    use: "babel-loader"
  },
  libStyle: {
    test: /\.css$/i,
    include: [
      /node_modules/,
      /styles\/lib/
    ],
    use: [
      "style-loader",
      {
        loader: "css-loader",
        options: {
          importLoaders: 1
        }
      },
      "postcss-loader"
    ]
  },
  style: {
    test: /\.css$/i,
    exclude: [
      /node_modules/,
      /styles\/lib/
    ],
    use: ExtractTextPlugin.extract({
      fallback: {
        loader: "style-loader",
        options: {
          sourceMap: true
        }
      },
      use: [
        {
          loader: "css-loader",
          options: {
            sourceMap: true,
            importLoaders: 1
          }
        },
        {
          loader: "postcss-loader",
          options: {
            sourceMap: true
          }
        }
      ]
    })
  },
  font: {
    test: /\.(woff2?|otf|ttf|eot)$/i,
    exclude: /node_modules/,
    use: {
      loader: "file-loader",
      options: {
        name: "fonts/[hash].[ext]"
      }
    }
  },
  image: {
    test: /\.(jpe?g|png|gif|svg)$/i,
    use: [
      {
        loader: "file-loader",
        options: {
          hash: "sha512",
          digest: "hex",
          name: "images/[hash].[ext]"
        }
      },
      "image-webpack-loader",
    ]
  }
};

module.exports = {
  entry: {
    "scripts/bundle.js": "./scripts/main.js",
    "styles/base.css": "./styles/base.css",
    "styles/main.css": "./styles/main.css",
    "styles/login.css": "./styles/login.css"
  },
  output: {
    path: path.resolve(__dirname, paths.dest),
    filename: "[name]",
    publicPath: `${paths.public}/`
  },
  module: {
    rules: Object.values(moduleRules)
  },
  devtool: "source-map",
  context: path.resolve(__dirname, paths.src),
  externals: {
    jquery: "jQuery"
  },
  plugins: [
    new CleanWebpackPlugin(paths.dest),
    new CopyWebpackPlugin([
      {
        from: "style.css"
      },
      {
        from: "**/*.{php,twig}"
      }
    ]),
    new ExtractTextPlugin("[name]"),
    new BrowserSyncPlugin({
      proxy: "wordpress",
      port: 3000,
      ui: {
        port: 3001
      },
      open: false
    })
  ]
};
