import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { formatFileSize } from '@/lib/utils';
import { cibest, cibestUpload } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { FileIcon, HardDrive, Upload } from 'lucide-react';
import { ChangeEvent, useRef, useState } from 'react';
import { DataTable } from '@/components/table-error/data-table';
import { columns } from '@/components/table-error/column';
import { toast } from 'sonner';
import { Spinner } from '@/components/ui/spinner';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'BPRS',
    href: cibest().url,
  },
];

export default function Cibest() {
  const inputRef = useRef<HTMLInputElement>(null);
  const [file, setFile] = useState<File | null>(null);
  const [isLoading, setIsLoading] = useState(false);
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
      const selectedFile = e.target.files[0];
      if (selectedFile) {
        const fileExtension = selectedFile.name.split('.').pop()?.toLowerCase();
        const validExtensions = ['xlsx', 'xls', 'csv'];

        if (validExtensions.includes(fileExtension || '')) {
          setFile(selectedFile);
        } else {
          toast.error('Format file tidak valid. Harap upload file dengan format .xlsx, .xls, atau .csv');
          if (inputRef.current) {
            inputRef.current.value = ''; // Reset the input
          }
        }
      }
    }
  }

  async function handleFileUpload() {
    if (!file) return;

    router.post(cibestUpload.url(), {
      file: file
    }, {
      onStart: () => {
        setIsLoading(true);
        return true;
      },
      onFinish: () => {
        setIsLoading(false);
      },
    });
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Survei CIBEST" />
      <div className="flex h-full flex-col flex-1 gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className='font-bold'>Survei BPRS</h1>
        <p>
          Unduh template excel <a href="/Template Cibest.xlsx" className='underline underline-offset-4'>disini</a>
        </p>
        <div className='flex flex-col w-fit h-fit gap-4'>
          <input
            ref={inputRef}
            type="file"
            accept=".xlsx,.xls,.csv"
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

          {file ? (
            <div className='flex gap-8 items-center'>
              <p className='text-sm flex gap-2 items-center'><FileIcon /> {file.name}</p>
              <p className='text-sm flex gap-2 items-center'><HardDrive /> {formatFileSize(file.size)}</p>
            </div>
          ) : (
            <p className='text-destructive'>* xlsx,xls,csv</p>
          )}

          <Button onClick={handleFileUpload} disabled={isLoading}>
            {isLoading && <Spinner />}
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
