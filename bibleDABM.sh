# && git checkout alpha && git pull origin alpha && git merge dev --no-edit && git push origin alpha
git pull origin dev && git push origin dev && git checkout beta && git pull origin beta && git merge dev --no-edit && git push origin beta && git checkout master && git pull origin master && git merge beta --no-edit && git push origin master && git checkout dev
