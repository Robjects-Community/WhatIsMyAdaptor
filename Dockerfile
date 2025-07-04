
#### Backend Dockerfile
# This Dockerfile is used to build the backend application using Node.js.
# We don't want to start from scratch.
# That is why we tell node here to use the current node image as base.
# Use the official Node.js image as base
# This Dockerfile is used to build the backend application using Node.js.
# We don't want to start from scratch.
# That is why we tell node here to use the current node image as base.
# Use the official Node.js image as base
FROM node:16-alpine
WORKDIR /usr/src/app
COPY package*.json ./
RUN npm i
COPY . .
EXPOSE 3000
CMD [ "npm", "start" ]

# Use the official PHP image with Apache


#### Frontend Dockerfile
# This Dockerfile is used to build the frontend application using Node.js and Nuxt.js.
# We don't want to start from scratch.
# That is why we tell node here to use the current node image as base.
FROM node:alpine3.11

# Create an application directory
RUN mkdir -p /app

# The /app directory should act as the main application directory
WORKDIR /app

# Copy the app package and package-lock.json file
COPY frontend/package*.json ./

# Install node packages
RUN npm install

# Copy or project directory (locally) in the current directory of our docker image (/app)
COPY frontend/ .

# Build the app
RUN npm run build

# Expose $PORT on container.
# We use a varibale here as the port is something that can differ on the environment.
EXPOSE $PORT

# Set host to localhost / the docker image
ENV NUXT_HOST=0.0.0.0

# Set app port
ENV NUXT_PORT=$PORT

# Set the base url
ENV PROXY_API=$PROXY_API

# Set the browser base url
ENV PROXY_LOGIN=$PROXY_LOGIN

# Start the app
CMD [ "npm", "start" ]
