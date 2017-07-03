start:
	trap "printf '\nFinishing up...\n' && docker-compose down" EXIT && \
	docker-compose up -d && \
	./node_modules/.bin/gulp

build:
	./node_modules/.bin/gulp build
