#!/usr/bin/env bash

# Prepare empty headless branch clone
echo "Preparing empty headless clone..."
git clone --branch headless --single-branch https://git:$GITHUB_TOKEN@github.com/NoelDeMartin/vocab.git headless-clone
mv headless-clone/.git .git.backup
rm headless-clone -rf
mkdir headless-clone
mv .git.backup headless-clone/.git

# Add files
echo "Adding files..."
cp headless/. headless-clone/ -r
cp scripts headless-clone/scripts -r
cp storage headless-clone/storage -r
cp vocab headless-clone/vocab

# Commit changes, if any
if [[ -z `git -C headless-clone status --short` ]]; then
    echo "There aren't any changes to apply!"
    exit
fi

cd headless-clone
echo "Committing changes..."

# Set up Github Actions bot user
# See https://github.community/t/github-actions-bot-email-address/17204/6
git config --local user.email "41898282+github-actions[bot]@users.noreply.github.com"
git config --local user.name "github-actions[bot]"

git add .
git commit -m "[auto-generated] Update headless files"
git push

echo "Headless files updated!"
