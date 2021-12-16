var gulp = require('gulp'),
	browserSync = require('browser-sync').create();

gulp.task('default', function(done) {
    browserSync.init({
		host: 'gulp.loc',
        proxy: "http://gulp.loc/",
		port: 81,
		open: 'external',
		notify: false,
		ghost: false,
    }
	);
	
	gulp.watch("./**/*.html").on('change', () => {
	  browserSync.reload();
      done();
    });
	gulp.watch("./**/*.scss").on('change', () => {
	  browserSync.reload();
      done();
    });
	gulp.watch("./**/*.css").on('change', () => {
	  browserSync.reload();
      done();
    });
    gulp.watch("./**/*.js").on('change', () => {
	  browserSync.reload();
      done();
    });
	gulp.watch("./**/*.php").on('change', () => {
	  browserSync.reload();
      done();
    });
	
});	