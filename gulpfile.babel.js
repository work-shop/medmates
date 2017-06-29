import gulp from "gulp";
import del from "del";
import plumber from "gulp-plumber";
import rename from "gulp-rename";
import flatten from "gulp-flatten";
import sourcemaps from "gulp-sourcemaps";
import babel from "gulp-babel";
import uglify from "gulp-uglify";
import postcss from "gulp-postcss";
import atImport from "postcss-import";
import cssnext from "postcss-cssnext";
import cssnano from "cssnano";
import browserSync from "browser-sync";

const basePaths = {
  src: "src",
  dest: "dist/wp-content/themes/custom"
};

const paths = {
  views: {
    src: `${basePaths.src}/**/*.php`,
    dest: basePaths.dest,
    watch: `${basePaths.src}/**/*.php`
  },
  templates: {
    src: `${basePaths.src}/templates/**/*.twig`,
    dest: `${basePaths.dest}/templates`,
    watch: `${basePaths.src}/templates/**/*.twig`
  },
  scripts: {
    src: `${basePaths.src}/scripts/main.js`,
    dest: basePaths.dest,
    watch: `${basePaths.src}/scripts/**/*.js`
  },
  styles: {
    src: `${basePaths.src}/styles/main.css`,
    dest: basePaths.dest,
    watch: `${basePaths.src}/styles/**/*.css`
  },
  fonts: {
    src: `${basePaths.src}/fonts/**/*.{woff,woff2}`,
    dest: `${basePaths.dest}/fonts`,
    watch: `${basePaths.src}/fonts/**/*.{woff,woff2}`
  },
  images: {
    src: `${basePaths.src}/images/**/*.{gif,jpg,png,svg}`,
    dest: `${basePaths.dest}/images`,
    watch: `${basePaths.src}/images/**/*.{gif,jpg,png,svg}`
  }
};

export const clean = () => del([basePaths.dest]);

export const views = () => gulp.src(paths.views.src)
  .pipe(flatten())
  .pipe(gulp.dest(paths.views.dest)
);

export const templates = () => gulp.src(paths.templates.src)
  .pipe(gulp.dest(paths.templates.dest)
);

export const scripts = () => gulp.src(paths.scripts.src)
  .pipe(sourcemaps.init())
  .pipe(babel())
  .pipe(uglify())
  .pipe(rename("bundle.js"))
  .pipe(sourcemaps.write("."))
  .pipe(gulp.dest(paths.scripts.dest)
);

export const styles = () => gulp.src(paths.styles.src)
  .pipe(sourcemaps.init())
  .pipe(plumber())
  .pipe(postcss([
    atImport(),
    cssnext({
      browsers: ["last 2 versions"]
    }),
    cssnano({
      autoprefixer: false
    })
  ]))
  .pipe(plumber.stop())
  .pipe(rename("style.css"))
  .pipe(sourcemaps.write("."))
  .pipe(gulp.dest(paths.styles.dest)
);

export const images = () => gulp.src(paths.images.src)
  .pipe(gulp.dest(paths.images.dest)
);

export const fonts = () => gulp.src(paths.fonts.src)
  .pipe(gulp.dest(paths.fonts.dest)
);

export const build = gulp.series(clean,
  gulp.parallel(views, templates, scripts, styles, fonts, images)
);

const bs = browserSync.create();

export const serve = (done) => bs.init({
  proxy: "localhost:8080"
});

export const reload = (done) => {
  bs.reload();
  done();
};

export const watch = () => {
  gulp.watch(paths.views.watch, gulp.series(views, reload));
  gulp.watch(paths.templates.watch, gulp.series(templates, reload));
  gulp.watch(paths.scripts.watch, gulp.series(scripts, reload));
  gulp.watch(paths.styles.watch, gulp.series(styles, reload));
  gulp.watch(paths.fonts.watch, gulp.series(fonts, reload));
  gulp.watch(paths.images.watch, gulp.series(images, reload));
};

export default gulp.series(clean, build,
  gulp.parallel(serve, watch)
);
