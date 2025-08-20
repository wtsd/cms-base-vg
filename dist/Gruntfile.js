module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('bower.json'),

    concat: {
      main: {
        files: {
          'web/js/libs.js': [
            'resources/assets/jquery/dist/jquery.min.js',
            'resources/assets/bootstrap/dist/js/bootstrap.min.js',
            'resources/assets/jquery-visible/jquery.visible.min.js',
            'resources/assets/photobox/photobox/jquery.photobox.js',
            'resources/assets/highlightjs/highlight.pack.js',
            'resources/assets/imagesloaded/imagesloaded.pkgd.min.js',
            //'resources/assets/masonry/dist/masonry.pkgd.min.js'            'resources/scripts/functions.js'
            ],
          'web/js/unpacked/app.js': [
            'resources/scripts/functions.js'
            ]

        }
      },
      admin: {
        files: {
          'web/js/unpacked/admin-app.js': [
              'resources/scripts/admin.js'
            ],
            'web/js/admin-libs.js': [
              'resources/assets/jquery/dist/jquery.min.js',
              'resources/assets/ckeditor/ckeditor.js',
              'resources/assets/bootstrap/dist/js/bootstrap.min.js',
              'resources/assets/bootstrap-tagsinput/src/tagsinput.js',

              'resources/assets/datatables.net/js/jquery.dataTables.min.js',
              'resources/assets/datatables/media/js/dataTables.jqueryui.min.js',
              'resources/assets/datatables/media/js/dataTables.bootstrap.min.js',
              'resources/assets/datatables/media/js/dataTables.jqueryui.min.js',
              'resources/assets/datatables/media/js/dataTables.material.min.js',
              'resources/assets/datatables.net-responsive/js/dataTables.responsive.min.js'
              
            ]
        }
      },
    },

    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        files: {
          'web/js/app.min.js': 'web/js/unpacked/app.js',
          /*'web/js/libs.min.js': 'web/js/unpacked/libs.js',
          'web/js/admin-libs.min.js': 'web/js/unpacked/admin-libs.js',*/
          //'web/js/admin-app.min.js': 'web/js/unpacked/admin-app.js',
          /*'web/js/admin-main.min.js': 'resources/scripts/admin-main.js'*/
        }
      }
    },

    traceur: {
    },
    less: {
      dist: {
        files: {
          //'web/css/unpacked/main.css': 'resources/styles/main.scss',
          'web/css/unpacked/primary.css': 'resources/styles/primary.less',
          'web/css/unpacked/admin.css': 'resources/styles/admin.less',
        }
      }
    },

    autoprefixer: {
        options: {
          browsers: ['last 2 versions', 'ie 8', 'ie 9'],
          diff: true
        },
        'web/css/unpacked/primary.css': 'web/css/unpacked/primary.css'
      },

    cssmin: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        shorthandCompacting: false,
        roundingPrecision: -1,
        sourceMap: true,
        keepSpecialComments: 0
      },
      target: {
        files: {
          'web/css/libs.min.css': [
            'resources/assets/normalize.css/normalize.css',
            'resources/assets/bootstrap/dist/css/bootstrap.min.css',
            'resources/assets/bootstrap/dist/css/bootstrap-theme.min.css',
            'resources/assets/photobox/photobox/photobox.css',
            'resources/assets/photobox/photobox/photobox.ie.css',
            'resources/assets/font-awesome/css/font-awesome.min.css',
            'resources/assets/highlightjs/styles/monokai_sublime.css',
          ],
          'web/css/primary.min.css': [
            'web/css/unpacked/primary.css',
          ],
          
          'web/css/admin-libs.css': [
            'resources/assets/normalize.css/normalize.css',
            'resources/assets/bootstrap/dist/css/bootstrap.min.css',
            //'resources/assets/bootstrap/dist/css/bootstrap-theme.min.css',
            'resources/assets/font-awesome/css/font-awesome.min.css',
            'resources/assets/datatables/media/css/dataTables.min.css',
            'resources/assets/datatables/media/css/dataTables.bootstrap.min.css',
          ],
          'web/css/admin.min.css': [
            'web/css/unpacked/admin.css',
          ]
        }
      }
    },

    copy: {
      main: {
        files: [
          {
            expand: true,
            src: 'resources/assets/font-awesome/fonts/*',
            dest: 'web/fonts/',
            flatten: true,
            filter: 'isFile',
          },
          {
            expand: true,
            src: 'resources/assets/bootstrap/dist/fonts/*',
            dest: 'web/fonts/',
            flatten: true,
            filter: 'isFile',
          },
          {
            expand: true,
            src: 'resources/assets/photobox/images/*',
            dest: 'web/images/',
            flatten: true,
            filter: 'isFile',
          }
        ]
      }
    },

    watch: {
      scripts: {
        files: ['web/js/unpacked/*.js', 'resources/scripts/*.js'],
        tasks: ['concat', 'uglify'],
        options: {
          spawn: false,
        }
      },
      css: {
        files: ['resources/styles/*.less', 'resources/styles/*/*.less'],
        tasks: ['less', 'autoprefixer', 'cssmin'],
        options: {
          spawn: false,
        }
      }
    }

  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-traceur');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-autoprefixer');

  grunt.registerTask('default', ['concat', 'traceur', 'uglify', 'less', 'autoprefixer', 'cssmin', 'copy']);
};