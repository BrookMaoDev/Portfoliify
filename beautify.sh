#!/bin/bash

echo "Starting installation of Prettier and the PHP plugin..."

# Install Prettier and the PHP plugin locally
npm install --save-dev prettier @prettier/plugin-php

echo "Prettier and the PHP plugin installed successfully."

# Create a .prettierrc file if it doesn't exist
if [ ! -f .prettierrc ]; then
    echo '{"plugins": ["@prettier/plugin-php"]}' >.prettierrc
    echo ".prettierrc file created."
fi

echo "Starting formatting with Prettier..."
# Use npx to run Prettier for PHP files along with JS, CSS, YAML, JSON, and Markdown files
npx prettier --write "**/*.{php,js,css,yml,json,md,prettierrc}"
echo "Formatting complete."
