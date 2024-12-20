@extends('Layout.shop_layout')
@section('content')

<section class="mt-8">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="product" id="product">
					<div>
						<div>
							<div class="slick-slider slick-initialized" dir="ltr">
								<div class="slick-list">
									<div class="slick-track" style="width: 8372px; opacity: 1; transform: translate3d(-2576px, 0px, 0px);">
										<div data-index="-1" tabindex="-1" class="slick-slide slick-cloned" aria-hidden="true"
										style="width: 644px;">
										</div>
										<div data-index="0" class="slick-slide" tabindex="-1" aria-hidden="true"
										style="outline: none; width: 644px;">
											<div>
												<div class="product productModal" id="productModal" tabindex="-1" style="width: 100%; display: inline-block;">
													<div class="zoom" style="background-image: url(&quot;/images/products/product-single-img-1.jpg&quot;); background-position: 84.8726% 85.3503%;">
														<img src="/images/products/product-single-img-1.jpg" alt="" class="product-image">
													</div>
												</div>
											</div>
										</div>
										<div data-index="1" class="slick-slide" tabindex="-1" aria-hidden="true"
										style="outline: none; width: 644px;">
											<div>
												<div class="product productModal" id="productModal" tabindex="-1" style="width: 100%; display: inline-block;">
													<div class="zoom" style="background-image: url(&quot;/images/products/product-single-img-2.jpg&quot;); background-position: 65.4459% 98.3015%;">
														<img src="/images/products/product-single-img-2.jpg" alt="" class="product-image">
													</div>
												</div>
											</div>
										</div>
										<div data-index="2" class="slick-slide" tabindex="-1" aria-hidden="true"
										style="outline: none; width: 644px;">
											<div>
												<div class="product productModal" id="productModal" tabindex="-1" style="width: 100%; display: inline-block;">
													<div class="zoom" style="background-image: url(&quot;/images/products/product-single-img-3.jpg&quot;); background-position: 50% 50%;">
														<img src="/images/products/product-single-img-3.jpg" alt="" class="product-image">
													</div>
												</div>
											</div>
										</div>
										<div data-index="3" class="slick-slide slick-active slick-current" tabindex="-1"
										aria-hidden="false" style="outline: none; width: 644px;">
											<div>
												<div class="product productModal" id="productModal" tabindex="-1" style="width: 100%; display: inline-block;">
													<div class="zoom" style="background-image: url(&quot;/images/products/product-single-img-4.jpg&quot;); background-position: 98.5669% 32.6964%;">
														<img src="/images/products/product-single-img-4.jpg" alt="" class="product-image">
													</div>
												</div>
											</div>
										</div>
										<div data-index="4" class="slick-slide" tabindex="-1" aria-hidden="true"
										style="outline: none; width: 644px;">
										</div>
										<div data-index="5" class="slick-slide" tabindex="-1" aria-hidden="true"
										style="outline: none; width: 644px;">
										</div>
										<div data-index="6" tabindex="-1" class="slick-slide slick-cloned" aria-hidden="true"
										style="width: 644px;">
											<div>
												<div class="product productModal" id="productModal" tabindex="-1" style="width: 100%; display: inline-block;">
													<div class="zoom" style="background-image: url(&quot;/images/products/product-single-img-1.jpg&quot;); background-position: 50% 50%;">
														<img src="/images/products/product-single-img-1.jpg" alt="" class="product-image">
													</div>
												</div>
											</div>
										</div>
										<div data-index="7" tabindex="-1" class="slick-slide slick-cloned" aria-hidden="true"
										style="width: 644px;">
											<div>
												<div class="product productModal" id="productModal" tabindex="-1" style="width: 100%; display: inline-block;">
													<div class="zoom" style="background-image: url(&quot;/images/products/product-single-img-2.jpg&quot;); background-position: 50% 50%;">
														<img src="/images/products/product-single-img-2.jpg" alt="" class="product-image">
													</div>
												</div>
											</div>
										</div>
										<div data-index="8" tabindex="-1" class="slick-slide slick-cloned" aria-hidden="true"
										style="width: 644px;">
											<div>
												<div class="product productModal" id="productModal" tabindex="-1" style="width: 100%; display: inline-block;">
													<div class="zoom" style="background-image: url(&quot;/images/products/product-single-img-3.jpg&quot;); background-position: 50% 50%;">
														<img src="/images/products/product-single-img-3.jpg" alt="" class="product-image">
													</div>
												</div>
											</div>
										</div>
										<div data-index="9" tabindex="-1" class="slick-slide slick-cloned" aria-hidden="true"
										style="width: 644px;">
											<div>
												<div class="product productModal" id="productModal" tabindex="-1" style="width: 100%; display: inline-block;">
													<div class="zoom" style="background-image: url(&quot;/images/products/product-single-img-4.jpg&quot;); background-position: 50% 50%;">
														<img src="/images/products/product-single-img-4.jpg" alt="" class="product-image">
													</div>
												</div>
											</div>
										</div>
										<div data-index="10" tabindex="-1" class="slick-slide slick-cloned" aria-hidden="true"
										style="width: 644px;">
										</div>
										<div data-index="11" tabindex="-1" class="slick-slide slick-cloned" aria-hidden="true"
										style="width: 644px;">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="product-tools">
					<div id="productModalThumbnails" class="thumbnails g-3 row">
						<div class="col-3">
							<div class="thumbnails-img">
								<img src="/images/products/product-single-img-1.jpg" alt="Image" class="">
							</div>
						</div>
						<div class="col-3">
							<div class="thumbnails-img">
								<img src="/images/products/product-single-img-2.jpg" alt="Image" class="">
							</div>
						</div>
						<div class="col-3">
							<div class="thumbnails-img">
								<img src="/images/products/product-single-img-3.jpg" alt="Image" class="">
							</div>
						</div>
						<div class="tns-nav-active col-3">
							<div class="thumbnails-img">
								<img src="/images/products/product-single-img-4.jpg" alt="Image" class="">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="ps-lg-10 mt-6 mt-md-0">
					<a class="mb-4 d-block" href="">
						Snack &amp; Munchies
					</a>
					<h1 class="mb-1">
						Haldiram's Sev Bhujia
					</h1>
					<div class="mb-4 d-flex align-items-center">
						<div class="d-flex gap-1">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="12"
							height="12" fill="currentColor" class="bi bi-star-fill text-warning">
								<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
								</path>
							</svg>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="12"
							height="12" fill="currentColor" class="bi bi-star-fill text-warning">
								<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
								</path>
							</svg>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="12"
							height="12" fill="currentColor" class="bi bi-star-fill text-warning">
								<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
								</path>
							</svg>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="12"
							height="12" fill="currentColor" class="bi bi-star-fill text-warning">
								<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
								</path>
							</svg>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="12"
							height="12" fill="currentColor" class="bi bi-star-half text-warning">
								<path d="M5.354 5.119 7.538.792A.52.52 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.54.54 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.5.5 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.6.6 0 0 1 .085-.302.51.51 0 0 1 .37-.245zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.56.56 0 0 1 .162-.505l2.907-2.77-4.052-.576a.53.53 0 0 1-.393-.288L8.001 2.223 8 2.226z">
								</path>
							</svg>
						</div>
						<a class="ms-2" href="#">
							(4 reviews)
						</a>
					</div>
					<div class="fs-4">
						<span class="fw-bold text-dark">
							$21.6
						</span>
						<span class="text-decoration-line-through text-muted">
							$24
						</span>
						<span>
							<small class="fs-6 ms-2 text-danger">
								10% Off
							</small>
						</span>
					</div>
					<hr class="my-6">
					<div class="mb-5 d-flex gap-1">
						<input class="btn-check" autocomplete="off" type="checkbox" name="size">
						<label tabindex="0" class="btn btn-outline-secondary">
							250g
						</label>
						<input class="btn-check" autocomplete="off" type="checkbox" name="size">
						<label tabindex="0" class="btn btn-outline-secondary">
							500g
						</label>
						<input class="btn-check" autocomplete="off" type="checkbox" name="size">
						<label tabindex="0" class="btn btn-outline-secondary">
							1kg
						</label>
					</div>
					<div class="w-20">
						<div class="input-spinner input-group">
							<button type="button" class="button-minus text-dark btn btn-primary btn-sm">
								-
							</button>
							<input class="quantity-field form-input form-control form-control-sm"
							type="number" value="1" name="quantity">
							<button type="button" class="button-plus text-dark btn btn-primary btn-sm">
								+
							</button>
						</div>
					</div>
					<div class="mt-3 justify-content-start g-2 align-items-center row">
						<div class="d-grid col-xxl-4 col-lg-4 col-md-5 col-5">
							<button type="button" class="btn btn-primary">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
								fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" class="me-2">
									<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z">
									</path>
									<line x1="3" y1="6" x2="21" y2="6">
									</line>
									<path d="M16 10a4 4 0 0 1-8 0">
									</path>
								</svg>
								Add to cart
							</button>
						</div>
						<div class="col-md-4 col-4">
							<button type="button" class="me-1 btn btn-light">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="1em"
								height="1em" fill="currentColor" class="bi bi-arrow-left-right">
									<path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5">
									</path>
								</svg>
							</button>
							<button type="button" class="btn btn-light">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
								fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round">
									<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
									</path>
								</svg>
							</button>
						</div>
					</div>
					<hr class="my-6">
					<div>
						<table class="table table-borderless mb-0">
							<tbody>
								<tr>
									<td>
										Product Code:
									</td>
									<td>
										FBB00255
									</td>
								</tr>
								<tr>
									<td>
										Availability:
									</td>
									<td>
										In Stock
									</td>
								</tr>
								<tr>
									<td>
										Type:
									</td>
									<td>
										Fruits
									</td>
								</tr>
								<tr>
									<td>
										Shipping:
									</td>
									<td>
										<small>
											01 day shipping.
											<span class="text-muted">
												( Free pickup today)
											</span>
										</small>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="mt-8">
						<div class="dropdown">
							<button type="button" id="react-aria9962645428-:r23:" aria-expanded="false"
							class="dropdown-toggle btn btn-outline-secondary">
								Share
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection