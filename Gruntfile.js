/**
 * Project task automation.
 *
 * Usage instructions: Can be found by running `grunt --help`.
 * Debug tip: Try running Grunt Tasks with the `--verbose` command
 */
module.exports = function (grunt) {

  var currentPlugin = {};

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-mysqldump');
  grunt.loadNpmTasks('grunt-jsbeautifier');

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    meta: {
      banner: '/*! <%=pkg.name%> - v<%=pkg.version%> (build <%=pkg.build%>) - ' + '<%=grunt.template.today("dddd, mmmm dS, yyyy, h:MM:ss TT")%> */'
    },
    
    compass: {
      dev: {
        options: {
          config: 'config.rb'
        } 
      } 
    },

    jshint: {
      all: ['public/scripts/*.js']
    },

    uglify: {
      options: {
        banner: '/*! <%=pkg.name%> - v<%=pkg.version%> (build <%=pkg.build%>) */',
        mangle: false,
        beautify: true,
        preserveComments: false
      },
      my_target: {
        files: {
          'public/js/script.js': ['public/js/script.js']
        }
      }
    },

    concat: {
      dist: {
        src: [
          'public/vendor/jquery/dist/jquery.js',
          'public/scripts/app.js'
        ],
        dest: 'public/js/script.js'
      }
    },

    cssmin: {
      combine: {
        files: {
          'public/css/style.min.css': [
             'public/css/reset.css', 
             'public/css/tools.css', 
             'public/css/style.css', 
             'public/css/responsive.css'
           ]
        }
      },
      minify: {
        expand: true,
        cwd: 'public/css/',
        src: ['style.min.css'],
        dest: 'public/css/'
      }
    },

    watch: {
      options: {
        livereload: true
      },
      scripts: {
        files: ['public/scripts/*.js'],
        tasks: ['concat','uglify']
      },
      sass: {
        files: ['public/stylesheets/*.scss'],
        tasks: ['compass','cssmin']
      },
      phtml: {
        files: ['module/**/*.phtml']
      },
      php: {
        files: ['module/**/*.php']
      }
    },

    compress: {
      main: {
        options: {
          mode: 'zip',
          pretty: true,
          archive: function () {
            return 'backup/' + grunt.template.today('yyyy-mm-dd') + '-' + currentPlugin.name + '.zip';
          }
        },
        files: [{
          expand: true,
          src: [
            '**',
            '!vendor/**',
            '!node_modules/**',
            '!public/vendor/**',
            '!public/backup/**'
          ],
        }]
      },
    },
    
    mysqlcfg: grunt.file.readJSON('database.json'),
    
    mysqldump: {
      dist: {
        user: '<%= mysqlcfg.local.user %>',
        pass: '<%= mysqlcfg.local.pass %>',
        host: '<%= mysqlcfg.local.host %>',
        port: '<%= mysqlcfg.local.port %>',
        dest: 'backup/',
        options: {
          compress: true,
          algorithm: 'zip',
          level: 5,
          both: false
        },
        databases: [
          'project_directory',
        ],
      },
    },

    jsbeautifier: {
      files: ['public/scripts/*.js'],
      options: {

      }
    }
    
  });

  grunt.registerTask('default', ['watch']);
  grunt.registerTask('js', ['jsbeautifier','concat', 'uglify']);
  grunt.registerTask('css', ['compass','cssmin']);
  grunt.registerTask('compile', ['concat', 'uglify', 'compass', 'cssmin']);
  grunt.task.registerTask('backup', 'Running compress website backup.', function (name) {
    if (!arguments.length) grunt.fail.fatal('Usage: grunt backup:<name>');
    currentPlugin.name = name;
    grunt.task.run('compress:main');
  });

}
