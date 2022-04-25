test:
	@docker build -t dimtester . > /dev/null
	@docker run --rm --name testdim dimtester