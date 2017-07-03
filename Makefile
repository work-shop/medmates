start:
	trap "printf '\nFinishing up...\n' && docker-compose down" EXIT && \
	docker-compose up -d && \
	gulp

build:
	gulp build
