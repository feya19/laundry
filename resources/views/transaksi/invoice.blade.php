@php
	use App\Library\Locale;
@endphp
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Invoice</title>
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
    	<link href="https://fonts.bunny.net/css?family=Helvetica" rel="stylesheet">
		<link rel="stylesheet" href="{{public_path('assets/build/styles/ltr-core.css')}}">
		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(4) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.total td:nth-child(4) {
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
			.text-right{
				text-align: right !important;
			}
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="4">
						<table>
							<tr>
								<td class="title">
									<img src="{{ public_path('assets/images/logo_light.png') }}" style="width: 100%; max-width: 300px" />
								</td>
								<td class="text-right" style="vertical-align: middle !important;">
									No Invoice: {{$model->no_invoice}}<br />
									Tanggal: {{Locale::humanDateTime($model->created_at)}}<br />
									Batas Waktu: {{Locale::humanDateTime($model->deadline)}}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="4">
						<table>
							<tr>
								<td>
									Outlet: {{$model->outlet->nama}}<br />
									{{$model->outlet->alamat}}<br />
									{{$model->outlet->telepon}}
								</td>

								<td class="text-right">
									{{$model->pelanggan->nama}}<br />
									{{$model->pelanggan->alamat}}<br />
									{{$model->pelanggan->telepon}}
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="details">
					<td>Pembayaran</td>
					<td></td>
					<td></td>
					<td>{!! $model->bayar >= $model->total ? '<span class="badge badge-success">Lunas</span>' : '<span class="badge badge-danger">Belum Lunas</span>'!!}</td>
				</tr>
				<tr class="heading">
					<td width="50%">Produk</td>
					<td width="20%">Harga</td>
					<td width="5%">Jumlah</td>
					<td width="25%">Total</td>
				</tr>
				@foreach ($model->transaksiDetail as $item)
				<tr class="item">
					<td>{{$item->produk->nama}}</td>
					<td>{{Locale::numberFormat($item->harga)}}</td>
					<td class="text-right">{{Locale::numberFormat($item->jumlah)}}</td>
					<td>{{Locale::numberFormat($item->total)}}</td>
				</tr>
				@endforeach
				<tr class="heading">
					<td></td>
					<td>Subtotal</td>
					<td></td>
					<td>{{Locale::numberFormat($model->subtotal)}}</td>
				</tr>
				@php
					$disc = 0;
					if($model->diskon > 0){
						$disc += $model->subtotal * $model->diskon / 100;
					}else if($model->potongan > 0){
						$disc += $model->potongan;
					}
				@endphp
				@if ($disc)
				<tr class="total">
					<td></td>
					<td>Diskon</td>
					<td>: </td>
					<td class="text-right">{{Locale::numberFormat($disc)}}</td>
				</tr>	
				@endif
				<tr class="total">
					<td></td>
					<td>Biaya Tambahan: </td>
					<td>: </td>
					<td class="text-right">{{Locale::numberFormat($model->biaya_tambahan)}}</td>
				</tr>
				<tr class="total">
					<td></td>
					<td>Total: </td>
					<td>: </td>
					<td class="text-right">{{Locale::numberFormat($model->total)}}</td>
				</tr>
				<tr class="total">
					<td></td>
					<td>Bayar: </td>
					<td>: </td>
					<td class="text-right">{{Locale::numberFormat($model->bayar)}}</td>
				</tr>
				<tr class="total">
					<td></td>
					<td>Kembali: </td>
					<td>: </td>
					<td class="text-right">{{Locale::numberFormat($model->kembali)}}</td>
				</tr>
			</table>
		</div>
	</body>
</html>
