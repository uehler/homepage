var lessSrcPath = 'less/';
var lessDestPath = 'css/';
//console.log(tplSrcPath);
module.exports = function (grunt) {
    grunt.initConfig({
        watch: { // watch for file changes
            less: {
                files: [
                    lessSrcPath + '*.less'
                ],
                tasks: [
                    'less',
                    'uncss',
                    'notify:watch'
                ]
            }
        },

        less: { // compile less to css
            options: {
                compress: true
            },
            src: {
                src: lessSrcPath + 'all.less',
                dest: lessDestPath + 'all.css'
            }
        },

        uncss: {
            dist: {
                files: {
                    'css/all.css': ['index.html']
                }
            }
        },

        notify: { // send notification if compiling has finished
            watch: {
                options: {
                    message: 'compiled successful',
                    success: true
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-uncss');
    grunt.loadNpmTasks('grunt-notify');


    grunt.registerTask('default', [
        'less',
        'uncss',
        'notify:watch'
    ]);
};