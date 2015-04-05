set :application, "sourcemapshapes"
set :repository,  "set your repository location here"


server 'shapes', :app, :web, :primary => true

set :deploy_to, "/var/www/shapes"
set :shared_files, "/var/www/shapes/shared"
set :repository, "git@github.com:bpurcell/shapes.git"
set :user, "sourcemap"


set :use_sudo, false


after "deploy", "deploy:sort_files_and_directories"

# Custom deployment tasks
namespace :deploy do
  desc "This is here to overide the original :restart"
  task :restart, :roles => [:app,:web] do
  end
  
  
  task :finalize_update, :roles => [:app,:web] do
    run "chmod -R g+w #{latest_release}" if fetch(:group_writable, true)
  end

  desc "Create additional directories and update permissions"
  task :sort_files_and_directories, :roles => [:app,:web] do

    run "mkdir #{latest_release}/system/cache"
    # move config files
    run "cp #{shared_files}/config.php #{latest_release}/application/config/config.php"
    run "cp #{shared_files}/database.php #{latest_release}/application/config/database.php"
    run "chmod 660 #{latest_release}/system/cache"
  end


end
