import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { formatFileSize } from '@/lib/utils';
import { baznas, baznasUpload } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { FileIcon, HardDrive, Upload } from 'lucide-react';
import { ChangeEvent, useRef, useState } from 'react';
import { DataTable } from '@/components/table-error/data-table';
import { columns } from '@/components/table-error/column';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'BAZNAS',
    href: baznas().url,
  },
];

export default function Baznas() {
  const inputRef = useRef<HTMLInputElement>(null);
  const [file, setFile] = useState<File | null>(null);
  const { flash } = usePage().props as {
    flash?: {
      importError: {
        row: number;
        attribute: string;
        error: string;
        value: string;
      }[]
    };
  };

  function handleFileChange(e: ChangeEvent<HTMLInputElement>) {
    if (e.target.files) {
      setFile(e.target.files[0]);
    }
  }

  async function handleFileUpload() {
    if (!file) return;

    router.post(baznasUpload.url(), {
      file: file
    });
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Survei BAZNAS" />
      <div className="flex h-full flex-col flex-1 gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className='font-bold'>Survei BAZNAS</h1>
        <div className='flex flex-col w-fit h-fit gap-4'>
          <input
            ref={inputRef}
            type="file"
            className="hidden"
            onChange={handleFileChange}
          />

          <Button
            variant="outline"
            onClick={() => inputRef.current?.click()}
            className="flex items-center gap-2"
          >
            <Upload className="h-4 w-4" />
            Upload File
          </Button>

          {file && (
            <div className='flex gap-8 items-center'>
              <p className='text-sm flex gap-2 items-center'><FileIcon /> {file.name}</p>
              <p className='text-sm flex gap-2 items-center'><HardDrive /> {formatFileSize(file.size)}</p>
            </div>
          )}

          <Button onClick={handleFileUpload}>
            Submit
          </Button>
        </div>
        {
          flash?.importError && (
            <div className="w-full h-fit flex flex-col gap-4 overflow-auto">
              <p className='text-destructive'><b>{flash.importError.length}</b> &nbsp; input tidak memenuhi standar data</p>
              <DataTable columns={columns} data={flash?.importError} />
            </div>
          )
        }
      </div>
    </AppLayout>
  );
}
