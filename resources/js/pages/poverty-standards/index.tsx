import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { povertyStandards, povertyStandardsStore, povertyStandardsUpdate, povertyStandardsDestroy } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import { Pencil, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Standar Kemiskinan',
    href: povertyStandards().url,
  },
];


// Helper function to format numbers as Indonesian Rupiah with thousand separators
const formatRupiah = (value: number): string => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

// Helper function to parse formatted Rupiah string back to number
const parseRupiah = (value: string): number => {
  // Remove all non-digit characters (except decimal point in case of decimals)
  const numericValue = value.replace(/[^0-9,.]/g, '').replace(/\./g, '');
  return parseFloat(numericValue) || 0;
};

export default function PovertyStandards({ povertyStandards } : { povertyStandards: PovertyStandard[] }) {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
  const [deletingItemId, setDeletingItemId] = useState<number | null>(null);
  const [editingItem, setEditingItem] = useState<PovertyStandard | null>(null);
  const [formData, setFormData] = useState({
    name: '',
    nilai_keluarga: 0,
    nilai_per_tahun: 0,
    log_natural: 0
  });

  // State for formatted values to show in inputs
  const [formattedNilaiKeluarga, setFormattedNilaiKeluarga] = useState('0');
  const [formattedNilaiPerTahun, setFormattedNilaiPerTahun] = useState('0');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    // Create updated formData with parsed values
    const updatedFormData = {
      ...formData,
      nilai_keluarga: parseRupiah(formattedNilaiKeluarga),
      nilai_per_tahun: parseRupiah(formattedNilaiPerTahun)
    };

    if (editingItem) {
      // Update existing item
      router.put(povertyStandardsUpdate.url(editingItem.id), updatedFormData);
    } else {
      // Create new item
      router.post(povertyStandardsStore.url(), updatedFormData);
    }
  };

  const handleEdit = (item: PovertyStandard) => {
    setEditingItem(item);
    setFormData({
      name: item.name,
      nilai_keluarga: item.nilai_keluarga,
      nilai_per_tahun: item.nilai_per_tahun,
      log_natural: item.log_natural
    });
    setFormattedNilaiKeluarga(item.nilai_keluarga.toString());
    setFormattedNilaiPerTahun(item.nilai_per_tahun.toString());
    setIsModalOpen(true);
  };

  const handleDelete = (id: number) => {
    setDeletingItemId(id);
    setIsDeleteModalOpen(true);
  };

  const confirmDelete = () => {
    if (deletingItemId !== null) {
      router.delete(povertyStandardsDestroy.url(deletingItemId));
      setIsDeleteModalOpen(false);
      setDeletingItemId(null);
    }
  };

  const cancelDelete = () => {
    setIsDeleteModalOpen(false);
    setDeletingItemId(null);
  };

  const resetForm = () => {
    setFormData({
      name: '',
      nilai_keluarga: 0,
      nilai_per_tahun: 0,
      log_natural: 0
    });
    setFormattedNilaiKeluarga('0');
    setFormattedNilaiPerTahun('0');
    setEditingItem(null);
  };

  const openAddModal = () => {
    resetForm();
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    resetForm();
  };

  // Handlers for currency inputs
  const handleNilaiKeluargaChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value;
    const numericValue = value.replace(/[^0-9]/g, ''); // Only allow numbers
    setFormattedNilaiKeluarga(numericValue);

    // Update the formData as well
    setFormData({
      ...formData,
      nilai_keluarga: parseInt(numericValue) || 0
    });
  };

  const handleNilaiPerTahunChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value;
    const numericValue = value.replace(/[^0-9]/g, ''); // Only allow numbers
    setFormattedNilaiPerTahun(numericValue);

    // Update the formData as well
    setFormData({
      ...formData,
      nilai_per_tahun: parseInt(numericValue) || 0
    });
  };

  // Format the display value with thousand separators for the input field
  const formatInputValue = (value: string): string => {
    if (!value) return '';
    // Remove non-digit characters, format with thousand separators, and return
    const numericValue = value.replace(/\D/g, '');
    if (!numericValue) return '';

    return new Intl.NumberFormat('id-ID', {
      maximumFractionDigits: 0
    }).format(Number(numericValue));
  };

  const displayNilaiKeluarga = formatInputValue(formattedNilaiKeluarga);
  const displayNilaiPerTahun = formatInputValue(formattedNilaiPerTahun);

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Standar Kemiskinan" />
      <div className="flex h-full flex-col flex-1 gap-4 overflow-x-auto rounded-xl p-4 bg-gray-50">
        <div className="flex justify-between items-center">
          <h1 className='font-bold text-2xl text-teal-600'>Standar Kemiskinan</h1>
          <Dialog open={isModalOpen} onOpenChange={setIsModalOpen}>
            <DialogTrigger asChild>
              <Button 
                onClick={openAddModal}
                className="bg-teal-500 hover:bg-teal-600 text-white"
              >
                <Plus className="h-4 w-4 mr-2" />
                Tambah Standar
              </Button>
            </DialogTrigger>
            <DialogContent>
              <DialogHeader>
                <DialogTitle>
                  {editingItem ? 'Edit Standar Kemiskinan' : 'Tambah Standar Kemiskinan'}
                </DialogTitle>
              </DialogHeader>
              <form onSubmit={handleSubmit} className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="name">Nama</Label>
                  <Input
                    id="name"
                    value={formData.name}
                    onChange={(e) => setFormData({...formData, name: e.target.value})}
                    required
                  />
                </div>
                
                <div className="space-y-2">
                  <Label htmlFor="nilai_keluarga">Nilai Keluarga</Label>
                  <div className="relative">
                    <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 font-medium">Rp</span>
                    <Input
                      id="nilai_keluarga"
                      type="text"
                      value={displayNilaiKeluarga}
                      onChange={handleNilaiKeluargaChange}
                      required
                      className="pl-10"
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="nilai_per_tahun">Nilai Per Tahun</Label>
                  <div className="relative">
                    <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 font-medium">Rp</span>
                    <Input
                      id="nilai_per_tahun"
                      type="text"
                      value={displayNilaiPerTahun}
                      onChange={handleNilaiPerTahunChange}
                      required
                      className="pl-10"
                    />
                  </div>
                </div>
                
                <div className="space-y-2">
                  <Label htmlFor="log_natural">Log Natural</Label>
                  <Input
                    id="log_natural"
                    type="number"
                    step="any"
                    value={formData.log_natural || ''}
                    onChange={(e) => setFormData({...formData, log_natural: Number(e.target.value)})}
                    required
                  />
                </div>
                
                <div className="flex justify-end space-x-2 pt-4">
                  <Button type="button" variant="outline" onClick={closeModal}>
                    Batal
                  </Button>
                  <Button type="submit" className="bg-teal-500 hover:bg-teal-600 text-white">
                    {editingItem ? 'Update' : 'Simpan'}
                  </Button>
                </div>
              </form>
            </DialogContent>
          </Dialog>
        </div>

        <Card className="mt-6">
          <CardHeader>
            <CardTitle>Daftar Standar Kemiskinan</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Keluarga</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Per Tahun</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Log Natural</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {povertyStandards.map((standard) => (
                    <tr key={standard.id}>
                      <td className="px-6 py-4 whitespace-nowrap">{standard.name}</td>
                      <td className="px-6 py-4 whitespace-nowrap">{formatRupiah(standard.nilai_keluarga)}</td>
                      <td className="px-6 py-4 whitespace-nowrap">{formatRupiah(standard.nilai_per_tahun)}</td>
                      <td className="px-6 py-4 whitespace-nowrap">{standard.log_natural}</td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex space-x-2">
                          <Button
                            variant="outline"
                            size="sm"
                            onClick={() => handleEdit(standard)}
                          >
                            <Pencil className="h-4 w-4" />
                          </Button>
                          <Button
                            variant="destructive"
                            size="sm"
                            onClick={() => handleDelete(standard.id)}
                          >
                            <Trash2 className="h-4 w-4" />
                          </Button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>

              {povertyStandards.length === 0 && (
                <div className="text-center py-8 text-gray-500">
                  Belum ada data standar kemiskinan
                </div>
              )}
            </div>
          </CardContent>
        </Card>

        {/* Delete Confirmation Dialog */}
        <Dialog open={isDeleteModalOpen} onOpenChange={setIsDeleteModalOpen}>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Konfirmasi Hapus</DialogTitle>
            </DialogHeader>
            <div className="py-4">
              <p>Apakah Anda yakin ingin menghapus standar kemiskinan ini?</p>
              <p className="text-sm text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div className="flex justify-end space-x-2">
              <Button variant="outline" onClick={cancelDelete}>
                Batal
              </Button>
              <Button variant="destructive" onClick={confirmDelete}>
                Hapus
              </Button>
            </div>
          </DialogContent>
        </Dialog>
      </div>
    </AppLayout>
  );
}