git pull origin dev && git checkout alpha && git pull origin alpha && git merge dev --no-edit && git push origin alpha && git checkout beta && git pull origin beta && git merge alpha --no-edit && git push origin beta && git checkout dev