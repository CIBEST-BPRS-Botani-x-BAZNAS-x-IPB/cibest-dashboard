import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { formatFileSize } from '@/lib/utils';
import { cibest, cibestUpload } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import { FileIcon, HardDrive, Upload } from 'lucide-react';
import { ChangeEvent, useRef, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'CIBEST',
        href: cibest().url,
    },
];

export default function Cibest() {
    const inputRef = useRef<HTMLInputElement>(null);
    const [file, setFile] = useState<File | null>(null);

    function handleFileChange(e: ChangeEvent<HTMLInputElement>) {
      if (e.target.files) {
        setFile(e.target.files[0]);
      }
    }

    async function handleFileUpload() {
      if (!file) return;

      router.post(cibestUpload.url(), {
        file: file
      });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Survei CIBEST" />
            <div className="flex h-full flex-col flex-1 gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className='font-bold'>Survei CIBEST</h1>
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
            </div>
        </AppLayout>
    );
}
