<?php
date_default_timezone_set("Asia/Jakarta");

$jam = date('H:i');

if ($jam > '05:30' && $jam < '10:00') {
    $salam = 'Pagi';
} elseif ($jam >= '10:00' && $jam < '15:00') {
    $salam = 'Siang';
} elseif ($jam < '18:00') {
    $salam = 'Sore';
} else {
    $salam = 'Malam';
}

$t_pasien = $db->query('SELECT COUNT(*) AS total_pasien FROM tb_soap GROUP BY no_rm');
$pow = $t_pasien->fetch_assoc();

$count=array($pow['total_pasien']);
echo count($count);

$t_dokter = $db->query('SELECT COUNT(*) AS total_dokter FROM tb_user WHERE role="dokter"');
$pow2 = $t_dokter->fetch_assoc();

$t_soap = $db->query('SELECT COUNT(*) AS total_soap FROM tb_soap ');
$pow3 = $t_soap->fetch_assoc();
?>
<div class="page-wrapper">
	<div class="page-breadcrumb">
		<div class="row">
			<div class="col-7 align-self-center">
				<h3 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo 'Selamat&nbsp;'. $salam; ?>, <?php echo $_SESSION['nama_lengkap'];?></h3>
				<div class="d-flex align-items-center">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb m-0 p-0">
							<li class="breadcrumb-item"><a href="index.php">Dashboard</a>
							</li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="col-5 align-self-center">
				<div class="customize-input float-right">
					<select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
						<option selected><?php echo date('Y') ?></option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="card-group">
			<div class="card border-right">
				<div class="card-body">
					<div class="d-flex d-lg-flex d-md-block align-items-center">
						<div>
							<div class="d-inline-flex align-items-center">
								<!-- <h2 class="text-dark mb-1 font-weight-medium"><?= count($count) ?></h2> -->
								<h2 class="text-dark mb-1 font-weight-medium">-</h2>
							</div>
							<h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Jumlah Pasien</h6>
						</div>
						<div class="ml-auto mt-md-3 mt-lg-0">
							<span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="card border-right">
				<div class="card-body">
					<div class="d-flex d-lg-flex d-md-block align-items-center">
						<div>
							<h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><?= $pow2['total_dokter']; ?></h2>
							<h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Jumlah Dokter</h6>
						</div>
						<div class="ml-auto mt-md-3 mt-lg-0">
							<span class="opacity-7 text-muted"><i data-feather="user"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="card border-right">
				<div class="card-body">
					<div class="d-flex d-lg-flex d-md-block align-items-center">
						<div>
							<div class="d-inline-flex align-items-center">
								<h2 class="text-dark mb-1 font-weight-medium"><?= $pow3['total_soap']; ?></h2>
							</div>
							<h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Riwayat SOAP</h6>
						</div>
						<div class="ml-auto mt-md-3 mt-lg-0">
							<span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-lg-4">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Notifikasi SOAP Dokter</h4>
						<div class="mt-4 activity">
							<!-- MULAI -->
							<?php 
                                $sesi= $_SESSION['nama_lengkap'];
                                $data = $db->query("SELECT a.kd_soap,a.no_rm,a.nama_pasien,a.kelas,a.dokter_jaga,						b.kd_soap,b.status,b.date_add
                                					FROM tb_soap a JOIN tb_soaplog b
                                					ON a.kd_soap=b.kd_soap
                                					ORDER BY b.kd_soap DESC LIMIT 5", 0);
                                // $data = $db->query("SELECT * FROM `tb_soap` ORDER BY `tb_soap`.`tgl_jaga` DESC", 0);
                                while($row = $data->fetch_assoc()) {
                            ?>
							<div class="d-flex align-items-start border-left-line">
								<div>
									<a href="javascript:void(0)" class="btn btn-cyan btn-circle mb-2 btn-item">
										<i data-feather="bell"></i>
									</a>
								</div>
								<div class="ml-3 mt-2">
									<?php if ($row['status']=='0') { ?>
										<h5 class="text-dark font-weight-medium mb-2">SOAP Baru dari <?= $row['dokter_jaga']; ?>!</h5>
										<p class="font-14 mb-2 text-muted">Dengan Pasien a.n <?= $row['nama_pasien']; ?><br> <?= $row['no_rm']; ?></p>
									<?php }elseif ($row['status']=='1') { ?>
										<h5 class="text-dark font-weight-medium mb-2">SOAP Update dari <?= $row['dokter_jaga']; ?>!</h5>
										<p class="font-14 mb-2 text-muted">Dengan Pasien a.n <?= $row['nama_pasien']; ?><br> <?= $row['no_rm']; ?></p>
									<?php } ?>
									<span class="font-weight-light font-14 mb-1 d-block text-muted"><?= tanggal_indo($row['date_add']); ?></span>
								</div>
							</div>
                            <?php } ?>
							<!-- END MULAI -->
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-12">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Grafik Riwayat SOAP Per-Pasien</h4>
						<div class="net-income mt-4 position-relative" style="height:294px;"></div>
						<ul class="list-inline text-center mt-5 mb-2">
							<li class="list-inline-item text-muted font-italic">Sales for this month</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="d-flex align-items-center mb-4">
							<h4 class="card-title">Top Leaders</h4>
							<div class="ml-auto">
								<div class="dropdown sub-dropdown">
									<button class="btn btn-link text-muted dropdown-toggle" type="button"
										id="dd1" data-toggle="dropdown" aria-haspopup="true"
										aria-expanded="false">
										<i data-feather="more-vertical"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
										<a class="dropdown-item" href="#">Insert</a>
										<a class="dropdown-item" href="#">Update</a>
										<a class="dropdown-item" href="#">Delete</a>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table no-wrap v-middle mb-0">
								<thead>
									<tr class="border-0">
										<th class="border-0 font-14 font-weight-medium text-muted">Team Lead
										</th>
										<th class="border-0 font-14 font-weight-medium text-muted px-2">Project
										</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Team</th>
										<th class="border-0 font-14 font-weight-medium text-muted text-center">
											Status
										</th>
										<th class="border-0 font-14 font-weight-medium text-muted text-center">
											Weeks
										</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Budget</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="border-top-0 px-2 py-4">
											<div class="d-flex no-block align-items-center">
												<div class="mr-3"><img
														src="assets/mode/images/users/widget-table-pic1.jpg"
														alt="user" class="rounded-circle" width="45"
														height="45" /></div>
												<div class="">
													<h5 class="text-dark mb-0 font-16 font-weight-medium">Hanna
														Gover</h5>
													<span class="text-muted font-14">hgover@gmail.com</span>
												</div>
											</div>
										</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">Elite Admin</td>
										<td class="border-top-0 px-2 py-4">
											<div class="popover-icon">
												<a class="btn btn-primary rounded-circle btn-circle font-12"
													href="javascript:void(0)">DS</a>
												<a class="btn btn-danger rounded-circle btn-circle font-12 popover-item"
													href="javascript:void(0)">SS</a>
												<a class="btn btn-cyan rounded-circle btn-circle font-12 popover-item"
													href="javascript:void(0)">RP</a>
												<a class="btn btn-success text-white rounded-circle btn-circle font-20"
													href="javascript:void(0)">+</a>
											</div>
										</td>
										<td class="border-top-0 text-center px-2 py-4"><i
												class="fa fa-circle text-primary font-12" data-toggle="tooltip"
												data-placement="top" title="In Testing"></i></td>
										<td
											class="border-top-0 text-center font-weight-medium text-muted px-2 py-4">
											35
										</td>
										<td class="font-weight-medium text-dark border-top-0 px-2 py-4">$96K
										</td>
									</tr>
									<tr>
										<td class="px-2 py-4">
											<div class="d-flex no-block align-items-center">
												<div class="mr-3"><img
														src="assets/mode/images/users/widget-table-pic2.jpg"
														alt="user" class="rounded-circle" width="45"
														height="45" /></div>
												<div class="">
													<h5 class="text-dark mb-0 font-16 font-weight-medium">Daniel
														Kristeen
													</h5>
													<span class="text-muted font-14">Kristeen@gmail.com</span>
												</div>
											</div>
										</td>
										<td class="text-muted px-2 py-4 font-14">Real Homes WP Theme</td>
										<td class="px-2 py-4">
											<div class="popover-icon">
												<a class="btn btn-primary rounded-circle btn-circle font-12"
													href="javascript:void(0)">DS</a>
												<a class="btn btn-danger rounded-circle btn-circle font-12 popover-item"
													href="javascript:void(0)">SS</a>
												<a class="btn btn-success text-white rounded-circle btn-circle font-20"
													href="javascript:void(0)">+</a>
											</div>
										</td>
										<td class="text-center px-2 py-4"><i
												class="fa fa-circle text-success font-12" data-toggle="tooltip"
												data-placement="top" title="Done"></i>
										</td>
										<td class="text-center text-muted font-weight-medium px-2 py-4">32</td>
										<td class="font-weight-medium text-dark px-2 py-4">$85K</td>
									</tr>
									<tr>
										<td class="px-2 py-4">
											<div class="d-flex no-block align-items-center">
												<div class="mr-3"><img
														src="assets/mode/images/users/widget-table-pic3.jpg"
														alt="user" class="rounded-circle" width="45"
														height="45" /></div>
												<div class="">
													<h5 class="text-dark mb-0 font-16 font-weight-medium">Julian
														Josephs
													</h5>
													<span class="text-muted font-14">Josephs@gmail.com</span>
												</div>
											</div>
										</td>
										<td class="text-muted px-2 py-4 font-14">MedicalPro WP Theme</td>
										<td class="px-2 py-4">
											<div class="popover-icon">
												<a class="btn btn-primary rounded-circle btn-circle font-12"
													href="javascript:void(0)">DS</a>
												<a class="btn btn-danger rounded-circle btn-circle font-12 popover-item"
													href="javascript:void(0)">SS</a>
												<a class="btn btn-cyan rounded-circle btn-circle font-12 popover-item"
													href="javascript:void(0)">RP</a>
												<a class="btn btn-success text-white rounded-circle btn-circle font-20"
													href="javascript:void(0)">+</a>
											</div>
										</td>
										<td class="text-center px-2 py-4"><i
												class="fa fa-circle text-primary font-12" data-toggle="tooltip"
												data-placement="top" title="Done"></i>
										</td>
										<td class="text-center text-muted font-weight-medium px-2 py-4">29</td>
										<td class="font-weight-medium text-dark px-2 py-4">$81K</td>
									</tr>
									<tr>
										<td class="px-2 py-4">
											<div class="d-flex no-block align-items-center">
												<div class="mr-3"><img
														src="assets/mode/images/users/widget-table-pic4.jpg"
														alt="user" class="rounded-circle" width="45"
														height="45" /></div>
												<div class="">
													<h5 class="text-dark mb-0 font-16 font-weight-medium">Jan
														Petrovic
													</h5>
													<span class="text-muted font-14">hgover@gmail.com</span>
												</div>
											</div>
										</td>
										<td class="text-muted px-2 py-4 font-14">Hosting Press HTML</td>
										<td class="px-2 py-4">
											<div class="popover-icon">
												<a class="btn btn-primary rounded-circle btn-circle font-12"
													href="javascript:void(0)">DS</a>
												<a class="btn btn-success text-white font-20 rounded-circle btn-circle"
													href="javascript:void(0)">+</a>
											</div>
										</td>
										<td class="text-center px-2 py-4"><i
												class="fa fa-circle text-danger font-12" data-toggle="tooltip"
												data-placement="top" title="In Progress"></i></td>
										<td class="text-center text-muted font-weight-medium px-2 py-4">23</td>
										<td class="font-weight-medium text-dark px-2 py-4">$80K</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
	<footer class="footer text-center text-muted">
		All Rights Reserved by Adminmart. Designed and Developed by <a
			href="https://wrappixel.com">Teknologi Informasi</a>.
	</footer>
</div>