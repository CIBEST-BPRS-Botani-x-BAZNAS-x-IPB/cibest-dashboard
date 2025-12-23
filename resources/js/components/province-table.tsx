import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
} from '@/components/ui/select';
import { QUADRANT_COLORS } from '@/lib/constants';
import { PovertyStandard, Province } from '@/types';
import { useState } from 'react';

interface ProvinceTableProps {
    provinces: Province[];
    povertyStandards: PovertyStandard[];
    allProvincesByStandard: Record<number, Province[]>;
}

export function ProvinceTable({
    povertyStandards,
    allProvincesByStandard,
}: ProvinceTableProps) {
    const [showModal, setShowModal] = useState(false);
    const [selectedStandard, setSelectedStandard] = useState<number>(0);

    const getQuadrantColor = (quadrant: string) => {
        const colors: Record<string, string> = {
            Q1: QUADRANT_COLORS.Q1,
            Q2: QUADRANT_COLORS.Q2,
            Q3: QUADRANT_COLORS.Q3,
            Q4: QUADRANT_COLORS.Q4,
        };
        return colors[quadrant] || '#ccc';
    };

    // Get provinces for the selected standard
    const provincesForStandard = allProvincesByStandard[selectedStandard];

    const handleStandardChange = (value: string) => {
        const standardId = parseInt(value);
        setSelectedStandard(standardId);
    };

    return (
        <>
            <div className="mb-6 rounded-lg bg-white p-6 shadow-sm">
                <div className="mb-4 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <h2 className="text-xl font-semibold text-gray-800">
                        Tabel Sebaran Responden
                    </h2>
                    <div className="flex w-full flex-col items-start gap-2 sm:w-auto sm:flex-row sm:items-center">
                        <Select
                            onValueChange={(value) =>
                                handleStandardChange(value)
                            }
                        >
                            <SelectTrigger>
                                {povertyStandards[selectedStandard].name}
                            </SelectTrigger>
                            <SelectContent>
                                {povertyStandards.map((standart, index) => (
                                    <SelectItem value={index.toLocaleString()}>
                                        {standart.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <Button
                            onClick={() => setShowModal(true)}
                            className="mt-1 w-full bg-orange-400 text-white hover:bg-orange-500 sm:mt-0 sm:w-auto"
                        >
                            View More
                        </Button>
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="bg-orange-400 text-white">
                                <th className="px-4 py-3 text-left">No.</th>
                                <th className="px-4 py-3 text-left">
                                    Provinsi
                                </th>
                                <th className="px-4 py-3 text-center">Q1</th>
                                <th className="px-4 py-3 text-center">Q2</th>
                                <th className="px-4 py-3 text-center">Q3</th>
                                <th className="px-4 py-3 text-center">Q4</th>
                                <th className="px-4 py-3 text-center">
                                    Jumlah
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {provincesForStandard
                                .slice(0, 6)
                                .map((province, idx) => (
                                    <tr
                                        key={province.id || idx}
                                        className="border-b border-gray-200 hover:bg-gray-50"
                                    >
                                        <td className="px-4 py-3">{idx + 1}</td>
                                        <td className="px-4 py-3">
                                            {province.name}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {province.Q1}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {province.Q2}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {province.Q3}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {province.Q4}
                                        </td>
                                        <td className="px-4 py-3 text-center font-semibold">
                                            {province.total}
                                        </td>
                                    </tr>
                                ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal for detailed province data */}
            <Dialog open={showModal} onOpenChange={setShowModal}>
                <DialogContent className="max-h-96 max-w-4xl overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>
                            Detail Sebaran Responden per Provinsi - {povertyStandards[selectedStandard].name}
                        </DialogTitle>
                    </DialogHeader>
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="bg-orange-400 text-white">
                                    <th className="px-4 py-3 text-left">No.</th>
                                    <th className="px-4 py-3 text-left">
                                        Provinsi
                                    </th>
                                    <th className="px-4 py-3 text-center">
                                        Q1
                                    </th>
                                    <th className="px-4 py-3 text-center">
                                        Q2
                                    </th>
                                    <th className="px-4 py-3 text-center">
                                        Q3
                                    </th>
                                    <th className="px-4 py-3 text-center">
                                        Q4
                                    </th>
                                    <th className="px-4 py-3 text-center">
                                        Jumlah
                                    </th>
                                    <th className="px-4 py-3 text-center">
                                        Dominan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {provincesForStandard.map((province, idx) => (
                                    <tr
                                        key={province.id || idx}
                                        className="border-b border-gray-200 hover:bg-gray-50"
                                    >
                                        <td className="px-4 py-3">{idx + 1}</td>
                                        <td className="px-4 py-3">
                                            {province.name}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {province.Q1}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {province.Q2}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {province.Q3}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {province.Q4}
                                        </td>
                                        <td className="px-4 py-3 text-center font-semibold">
                                            {province.total}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            <span
                                                className="rounded px-2 py-1 text-xs font-semibold text-white"
                                                style={{
                                                    backgroundColor:
                                                        getQuadrantColor(
                                                            province.dominant,
                                                        ),
                                                }}
                                            >
                                                {province.dominant}
                                            </span>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </DialogContent>
            </Dialog>
        </>
    );
}
