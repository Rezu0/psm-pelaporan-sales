export default () => {
    let semua = [];

    return {
        cari: '',
        perHalaman: 10,
        halaman: 1,
        total: 0,

        init() {
            semua = Array.from(this.$refs.badan.querySelectorAll('tr[data-teks]'));
            this.$watch('cari', () => { this.halaman = 1; this.perbarui(); });
            this.$watch('perHalaman', () => { this.halaman = 1; this.perbarui(); });
            this.$watch('halaman', () => this.perbarui());
            this.perbarui();
        },

        get jumlahHalaman() {
            return Math.max(1, Math.ceil(this.total / this.perHalaman));
        },
        get dari() {
            return this.total === 0 ? 0 : (this.halaman - 1) * this.perHalaman + 1
        }
    }
}