<?php
session_start();
require_once __DIR__ . '/includes/api.php';

$pageTitle = 'Beranda';
$pageSubtitle = '';
$currentMenu = 'dashboard';

// Fetch Data
$apiResponse = api_get('/titik-risiko');
$dataTitik = $apiResponse['success'] ? $apiResponse['data'] : [];

// Statistics
$total = count($dataTitik);
$rendah = 0;
$sedang = 0;
$tinggi = 0;

foreach ($dataTitik as $t) {
    $lvl = strtolower($t['level_risiko_awal'] ?? '');
    if ($lvl === 'rendah') $rendah++;
    elseif ($lvl === 'sedang') $sedang++;
    elseif ($lvl === 'tinggi') $tinggi++;
}

$pct_rendah = $total > 0 ? round(($rendah / $total) * 100) : 0;
$pct_sedang = $total > 0 ? round(($sedang / $total) * 100) : 0;
$pct_tinggi = $total > 0 ? round(($tinggi / $total) * 100) : 0;

include 'includes/header.php';
?>

<!-- Hero -->
<div class="hero">
    <div class="hero-content">
        <h1>Selamat Datang di BAMUK!</h1>
        <p class="hero-sub">Pantau informasi resiko DBD di lingkungan sekitar Anda.</p>
        <div class="hero-feats">
            <div class="hf">
                <div class="hf-icon hf-green"><i class="ph-fill ph-shield-check"></i></div>
                <div class="hf-txt">
                    <strong>Deteksi Dini</strong>
                    <span>Pantau risiko DBD secara real-time di wilayah Anda.</span>
                </div>
            </div>
            <div class="hf">
                <div class="hf-icon hf-green"><i class="ph-fill ph-users-three"></i></div>
                <div class="hf-txt">
                    <strong>Aksi Bersama</strong>
                    <span>Laporkan dan lakukan 3M Plus untuk lingkungan yang sehat.</span>
                </div>
            </div>
            <div class="hf">
                <div class="hf-icon hf-orange"><i class="ph-fill ph-bell-ringing"></i></div>
                <div class="hf-txt">
                    <strong>Informasi Akurat</strong>
                    <span>Data terpercaya untuk keputusan yang tepat.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan DBD -->
<div class="ringkasan">
    <h2 class="ringkasan-title"><i class="ph-fill ph-chart-bar"></i> Ringkasan DBD di sekitarmu</h2>

    <div class="rk-cards">
        <!-- RENDAH -->
        <div class="rk-card rk-border-green">
            <div class="rk-top">
                <div class="rk-icon rk-icon-green"><i class="ph-fill ph-check-circle"></i></div>
                <div class="rk-title-area">
                    <div class="rk-name rk-name-green">Risiko Rendah</div>
                    <div class="rk-status">Aman</div>
                </div>
                <div class="rk-pct rk-pct-green">
                    <span class="rk-pct-num" id="pct-rendah"><?= $pct_rendah ?>%</span>
                    <span class="rk-pct-label">dari total<br>wilayah</span>
                </div>
            </div>
            <div class="rk-mid">
                <span class="rk-big rk-big-green" id="count-rendah"><?= $rendah ?></span>
                <span class="rk-unit">wilayah</span>
            </div>
            <div class="rk-foot rk-foot-green"><i class="ph-fill ph-trend-up"></i> Dominan</div>
        </div>

        <!-- SEDANG -->
        <div class="rk-card rk-border-orange">
            <div class="rk-top">
                <div class="rk-icon rk-icon-orange"><i class="ph-fill ph-warning"></i></div>
                <div class="rk-title-area">
                    <div class="rk-name rk-name-orange">Risiko Sedang</div>
                    <div class="rk-status">Waspada</div>
                </div>
                <div class="rk-pct rk-pct-orange">
                    <span class="rk-pct-num" id="pct-sedang"><?= $pct_sedang ?>%</span>
                    <span class="rk-pct-label">dari total<br>wilayah</span>
                </div>
            </div>
            <div class="rk-mid">
                <span class="rk-big rk-big-orange" id="count-sedang"><?= $sedang ?></span>
                <span class="rk-unit">wilayah</span>
            </div>
            <div class="rk-foot rk-foot-orange"><i class="ph-fill ph-minus-circle"></i> Perlu Diwaspadai</div>
        </div>

        <!-- TINGGI -->
        <div class="rk-card rk-border-red">
            <div class="rk-top">
                <div class="rk-icon rk-icon-red"><i class="ph-fill ph-fire"></i></div>
                <div class="rk-title-area">
                    <div class="rk-name rk-name-red">Risiko Tinggi</div>
                    <div class="rk-status">Bahaya</div>
                </div>
                <div class="rk-pct rk-pct-red">
                    <span class="rk-pct-num" id="pct-tinggi"><?= $pct_tinggi ?>%</span>
                    <span class="rk-pct-label">dari total<br>wilayah</span>
                </div>
            </div>
            <div class="rk-mid">
                <span class="rk-big rk-big-red" id="count-tinggi"><?= $tinggi ?></span>
                <span class="rk-unit">wilayah</span>
            </div>
            <div class="rk-foot rk-foot-red"><i class="ph-fill ph-arrow-circle-down"></i> Prioritas Penanganan</div>
        </div>
    </div>

    <!-- Chart + Tips row -->
    <div class="ct-row">
        <div class="ct-chart">
            <h3><i class="ph-fill ph-trend-up"></i> Tren Risiko DBD (7 Hari Terakhir)</h3>
            <div class="ct-chart-wrap"><canvas id="trendChart"></canvas></div>
        </div>
        <div class="ct-tips">
            <h3><i class="ph-fill ph-shield-check"></i> Tips 3M Plus</h3>
            <div class="tips-row">
                <div class="tip-item">
                    <div class="tip-circle"><i class="ph-fill ph-drop"></i></div>
                    <strong>Menguras</strong>
                    <span>Bersihkan tempat penampungan air secara rutin.</span>
                </div>
                <div class="tip-item">
                    <div class="tip-circle"><i class="ph-fill ph-lock-simple"></i></div>
                    <strong>Menutup</strong>
                    <span>Tutup rapat tempat penampungan air.</span>
                </div>
                <div class="tip-item">
                    <div class="tip-circle"><i class="ph-fill ph-recycle"></i></div>
                    <strong>Mengubur</strong>
                    <span>Kubur atau daur ulang barang bekas.</span>
                </div>
                <div class="tip-item">
                    <div class="tip-circle"><i class="ph-fill ph-magnifying-glass"></i></div>
                    <strong>Memantau</strong>
                    <span>Pantau jentik nyamuk di lingkungan Anda.</span>
                </div>
                <div class="tip-item">
                    <div class="tip-circle"><i class="ph-fill ph-plant"></i></div>
                    <strong>Menanam</strong>
                    <span>Tanam tanaman pengusir nyamuk.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer banner -->
    <div class="foot-banner">
        <span><i class="ph-fill ph-shield-check"></i> Jaga kebersihan lingkungan dan lakukan 3M Plus secara rutin.</span>
        <span class="foot-sep">|</span>
        <span><i class="ph-fill ph-heart"></i> Lingkungan Bersih, Hidup Sehat <i class="ph-fill ph-leaf" style="color:#16a34a"></i></span>
    </div>
</div>

<script>
(function(){
    function genLabels(){
        var L=[];
        for(var i=6;i>=0;i--){var d=new Date();d.setDate(d.getDate()-i);L.push(d.toLocaleDateString('id-ID',{day:'numeric',month:'short'}));}
        return L;
    }
    var myChart=null;
    function drawChart(r,s,t){
        var el=document.getElementById('trendChart');
        if(!el)return;
        var rD=[Math.max(0,r-2),Math.max(0,r-1),r,Math.max(0,r-1),r,Math.max(0,r+1),r];
        var sD=[Math.max(0,s+1),s,Math.max(0,s-1),s,Math.max(0,s+1),s,s];
        var tD=[t,t,Math.max(0,t-1),t,Math.max(0,t+1),t,t];
        myChart=new Chart(el,{
            type:'line',
            data:{
                labels:genLabels(),
                datasets:[
                    {label:'Risiko Rendah',data:rD,borderColor:'#22c55e',backgroundColor:'#22c55e',tension:.4,pointRadius:3,borderWidth:2,fill:false},
                    {label:'Risiko Sedang',data:sD,borderColor:'#f59e0b',backgroundColor:'#f59e0b',tension:.4,pointRadius:3,borderWidth:2,fill:false},
                    {label:'Risiko Tinggi',data:tD,borderColor:'#ef4444',backgroundColor:'#ef4444',tension:.4,pointRadius:3,borderWidth:2,fill:false}
                ]
            },
            options:{
                responsive:true,
                maintainAspectRatio:false,
                plugins:{legend:{position:'bottom',labels:{boxWidth:10,usePointStyle:true,font:{size:10}}}},
                scales:{y:{beginAtZero:true,ticks:{font:{size:10}}},x:{ticks:{font:{size:9}}}}
            }
        });
    }
    function update(){
        fetch('http://localhost:8000/api/titik-risiko')
        .then(function(r){return r.json();})
        .then(function(d){
            if(d.status==='success'&&d.data){
                var r=0,s=0,t=0;
                d.data.forEach(function(x){
                    var l=(x.level_risiko_awal||'').toLowerCase();
                    if(l==='rendah')r++;else if(l==='sedang')s++;else if(l==='tinggi')t++;
                });
                var tot=r+s+t;
                document.getElementById('count-rendah').textContent=r;
                document.getElementById('pct-rendah').textContent=(tot?Math.round(r/tot*100):0)+'%';
                document.getElementById('count-sedang').textContent=s;
                document.getElementById('pct-sedang').textContent=(tot?Math.round(s/tot*100):0)+'%';
                document.getElementById('count-tinggi').textContent=t;
                document.getElementById('pct-tinggi').textContent=(tot?Math.round(t/tot*100):0)+'%';
                if(!myChart)drawChart(r,s,t);
            }
        }).catch(function(e){console.error('API:',e);});
    }
    update();
    setInterval(update,10000);
})();
</script>

<?php include 'includes/footer.php'; ?>
