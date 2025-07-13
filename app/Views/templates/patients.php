<div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container mt-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="fw-bold" style="color:#7a5fc0; font-size:2.2rem; letter-spacing:-1px;">Lists of Patients</h1>
                    <button class="btn btn-primary" style="background: #c7aefc; border: none; border-radius: 12px; font-weight: 700; font-size:1.1rem; box-shadow: 0 2px 8px #c7aefc44; padding: 10px 28px;">+ Add New Patient</button>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle patients-table" style="background: #fff; border-radius: 22px; box-shadow: 0 4px 32px #e6e6f6; overflow: hidden;">
                        <thead style="background: #fff;">
                            <tr style="color: #7a5fc0; font-weight: 800; font-size:1.08rem;">
                                <th style="border: none; padding: 18px 18px 12px 32px;">Name</th>
                                <th style="border: none; padding: 18px 12px 12px 12px;">ID</th>
                                <th style="border: none; padding: 18px 12px 12px 12px;">Email</th>
                                <th style="border: none; padding: 18px 12px 12px 12px;">Phone number</th>
                                <th style="border: none; padding: 18px 12px 12px 12px;">Address</th>
                                <th style="border: none; padding: 18px 12px 12px 12px;">Status</th>
                                <th style="border: none; padding: 18px 18px 12px 12px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $patient): ?>
                            <tr style="background: #fff; border-bottom: 1.5px solid #f0eafd; transition: background 0.2s;">
                                <td style="min-width: 220px; padding: 18px 18px 18px 32px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:48px; height:48px; border-radius:50%; background:#ede6fa; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.2rem; color:#a89ad7;">
                                            <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div style="font-weight:800; color:#3d2e6e; font-size:1.13rem;"> <?= htmlspecialchars($patient['name']) ?> </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-weight:700; color:#7a5fc0; padding: 18px 12px;"> <?= htmlspecialchars($patient['id']) ?> </td>
                                <td style="color:#5e5e7a; padding: 18px 12px;"> <?= htmlspecialchars($patient['email']) ?> </td>
                                <td style="color:#5e5e7a; padding: 18px 12px;"> <?= htmlspecialchars($patient['phone']) ?> </td>
                                <td style="color:#5e5e7a; min-width:180px; padding: 18px 12px;">
                                    <?= htmlspecialchars($patient['address']) ?>
                                </td>
                                <td style="padding: 18px 12px;">
                                    <?php 
                                    $status = $patient['status'] ?? 'active';
                                    $statusClass = $status === 'active' ? 'bg-success' : 'bg-danger';
                                    $statusText = ucfirst($status);
                                    ?>
                                    <span class="badge <?= $statusClass ?>" style="font-weight:600; border-radius:8px; padding:8px 12px;">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td style="padding: 18px 18px 18px 12px;">
                                    <a href="#" title="View" style="margin-right:10px;"><i class="fas fa-eye" style="color:#7a5fc0; font-size:1.15rem;"></i></a>
                                    <a href="#" title="Edit" style="margin-right:10px;"><i class="fas fa-edit" style="color:#7a5fc0; font-size:1.15rem;"></i></a>
                                    <a href="#" title="Delete" style="margin-right:10px;"><i class="fas fa-trash" style="color:#e57373; font-size:1.15rem;"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>