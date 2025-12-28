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
import { QuadrantData } from '@/types';
import { useState, useRef } from 'react';
import {
    Bar,
    BarChart,
    CartesianGrid,
    Cell,
    Legend,
    Pie,
    PieChart,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis,
} from 'recharts';

interface QuadrantDistributionProps {
    quadrantData?: QuadrantData[];
}

export function QuadrantDistribution({
    quadrantData,
}: QuadrantDistributionProps) {
    const [showModal, setShowModal] = useState(false);
    const [standard, setStandart] = useState(0);
    const chartRef = useRef<HTMLDivElement>(null);

    // Default to empty array if quadrantData is undefined
    const data = quadrantData || [];

    const currentStandard = data.length > 0 ? data[standard] : null;

    const barData = currentStandard
        ? [
              {
                  name: 'Q1',
                  Before: currentStandard.before[1] || 0,
                  After: currentStandard.after[1] || 0,
              },
              {
                  name: 'Q2',
                  Before: currentStandard.before[2] || 0,
                  After: currentStandard.after[2] || 0,
              },
              {
                  name: 'Q3',
                  Before: currentStandard.before[3] || 0,
                  After: currentStandard.after[3] || 0,
              },
              {
                  name: 'Q4',
                  Before: currentStandard.before[4] || 0,
                  After: currentStandard.after[4] || 0,
              },
          ]
        : [];

    // Calculate the percentage for each quadrant based on total
    const calculatePercentage = (
        quadrantNum: number,
        period: 'before' | 'after' = 'after',
    ): number => {
        if (!currentStandard) return 0;
        const quadrantCount =
            period === 'after'
                ? currentStandard.after[quadrantNum] || 0
                : currentStandard.before[quadrantNum] || 0;
        const total =
            period === 'after'
                ? Object.values(currentStandard.after).reduce(
                      (sum, count) => sum + (count || 0),
                      0,
                  )
                : Object.values(currentStandard.before).reduce(
                      (sum, count) => sum + (count || 0),
                      0,
                  );
        return total > 0 ? Math.round((quadrantCount / total) * 100) : 0;
    };

    const pieData = currentStandard
        ? [
              {
                  name: 'Q1 Sejahtera',
                  value: calculatePercentage(1),
                  color: QUADRANT_COLORS.Q1,
              },
              {
                  name: 'Q2 Material',
                  value: calculatePercentage(2),
                  color: QUADRANT_COLORS.Q2,
              },
              {
                  name: 'Q3 Spiritual',
                  value: calculatePercentage(3),
                  color: QUADRANT_COLORS.Q3,
              },
              {
                  name: 'Q4 Absolut',
                  value: calculatePercentage(4),
                  color: QUADRANT_COLORS.Q4,
              },
          ]
        : [];

    const downloadChartAsImage = () => {
        const svgElement = chartRef.current?.querySelector('svg');
        if (!svgElement) {
            alert('Chart not found. Please try again.');
            return;
        }

        try {
            // Clone the SVG to avoid modifying the original
            const clonedSvg = svgElement.cloneNode(true) as SVGElement;
            
            // Get dimensions
            const bbox = svgElement.getBoundingClientRect();
            const width = bbox.width;
            const height = bbox.height;
            
            // Set explicit dimensions and namespace
            clonedSvg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            clonedSvg.setAttribute('width', String(width));
            clonedSvg.setAttribute('height', String(height));
            
            // Serialize the cloned SVG
            const svgString = new XMLSerializer().serializeToString(clonedSvg);
            
            // Create canvas
            const canvas = document.createElement('canvas');
            canvas.width = width * 2; // 2x for better quality
            canvas.height = height * 2;
            const ctx = canvas.getContext('2d');
            
            if (!ctx) {
                alert('Failed to create canvas.');
                return;
            }
            
            // Create blob and object URL
            const blob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            
            // Create image
            const img = new Image();
            
            img.onload = () => {
                // Scale the context
                ctx.scale(2, 2);
                
                // White background
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, width, height);
                
                // Draw image
                ctx.drawImage(img, 0, 0, width, height);
                
                // Convert canvas to blob and download
                canvas.toBlob((pngBlob) => {
                    if (pngBlob) {
                        const pngUrl = URL.createObjectURL(pngBlob);
                        const link = document.createElement('a');
                        link.href = pngUrl;
                        link.download = `quadrant-distribution-${data[standard]?.name || 'chart'}.png`;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        URL.revokeObjectURL(pngUrl);
                    } else {
                        alert('Failed to create PNG image.');
                    }
                    URL.revokeObjectURL(url);
                }, 'image/png');
            };
            
            img.onerror = () => {
                alert('Failed to load SVG. Please try again.');
                URL.revokeObjectURL(url);
            };
            
            img.src = url;
            
        } catch (error) {
            console.error('Export error:', error);
            alert('Failed to export chart. Please try again.');
        }
    };

    return (
        <>
            <div className="mb-6 rounded-lg bg-white p-6 shadow-sm">
                <div className="mb-6 flex items-center justify-between">
                    <h2 className="text-xl font-semibold text-gray-800">
                        Distribusi Kuadran
                    </h2>
                    <div className="flex items-center gap-2">
                        {data && data.length > 0 && (
                            <Select
                                onValueChange={(value) =>
                                    setStandart(parseInt(value))
                                }
                            >
                                <SelectTrigger>
                                    {data[standard].name}
                                </SelectTrigger>
                                <SelectContent>
                                    {data.map((standart, index) => (
                                        <SelectItem
                                            value={index.toLocaleString()}
                                        >
                                            {standart.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        )}
                        <Button
                            onClick={() => setShowModal(true)}
                            className="bg-teal-500 text-white hover:bg-teal-600"
                        >
                            View More
                        </Button>
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    {/* Pie Chart */}
                    <div className="lg:col-span-1">
                        <ResponsiveContainer width="100%" height={300}>
                            <PieChart>
                                <Pie
                                    data={pieData}
                                    cx="50%"
                                    cy="50%"
                                    labelLine={false}
                                    label={({ name, value }) =>
                                        `${name}: ${value}%`
                                    }
                                    outerRadius={80}
                                    fill="#8884d8"
                                    dataKey="value"
                                >
                                    {pieData.map((entry, index) => (
                                        <Cell
                                            key={`cell-${index}`}
                                            fill={entry.color}
                                        />
                                    ))}
                                </Pie>
                                <Tooltip formatter={(value) => `${value}%`} />
                            </PieChart>
                        </ResponsiveContainer>
                    </div>

                    {/* Quadrant Grid */}
                    <div className="lg:col-span-2">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="rounded-lg border-2 border-blue-300 bg-blue-100 p-4">
                                <h3 className="mb-2 font-semibold text-blue-700">
                                    Quadrant II
                                </h3>
                                <p className="text-sm text-blue-700">
                                    Material (-,+)
                                </p>
                                <p className="mt-2 text-2xl font-bold text-blue-700">
                                    {currentStandard
                                        ? calculatePercentage(2, 'after') + '%'
                                        : '0%'}
                                </p>
                            </div>
                            <div className="rounded-lg border-2 border-green-300 bg-green-100 p-4">
                                <h3 className="mb-2 font-semibold text-green-700">
                                    Quadrant I
                                </h3>
                                <p className="text-sm text-green-700">
                                    Sejahtera (+,+)
                                </p>
                                <p className="mt-2 text-2xl font-bold text-green-700">
                                    {currentStandard
                                        ? calculatePercentage(1, 'after') + '%'
                                        : '0%'}
                                </p>
                            </div>
                            <div className="rounded-lg border-2 border-red-300 bg-red-100 p-4">
                                <h3 className="mb-2 font-semibold text-red-700">
                                    Quadrant IV
                                </h3>
                                <p className="text-sm text-red-700">
                                    Absolut (-,-)
                                </p>
                                <p className="mt-2 text-2xl font-bold text-red-700">
                                    {currentStandard
                                        ? calculatePercentage(4, 'after') + '%'
                                        : '0%'}
                                </p>
                            </div>
                            <div className="rounded-lg border-2 border-yellow-300 bg-yellow-100 p-4">
                                <h3 className="mb-2 font-semibold text-yellow-700">
                                    Quadrant III
                                </h3>
                                <p className="text-sm text-yellow-700">
                                    Spiritual (+,-)
                                </p>
                                <p className="mt-2 text-2xl font-bold text-yellow-700">
                                    {currentStandard
                                        ? calculatePercentage(3, 'after') + '%'
                                        : '0%'}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Modal for Before/After Comparison */}
            <Dialog open={showModal} onOpenChange={setShowModal}>
                <DialogContent className="max-w-2xl">
                    <DialogHeader>
                        <DialogTitle className="flex items-center justify-between">
                            <span>Distribusi Kuadran - Perbandingan Before & After</span>
                            <Button
                                onClick={downloadChartAsImage}
                                className="bg-teal-500 text-white hover:bg-teal-600"
                                size="sm"
                            >
                                Export
                            </Button>
                        </DialogTitle>
                    </DialogHeader>
                    <div ref={chartRef} className="h-96 w-full bg-white p-4">
                        <ResponsiveContainer width="100%" height="100%">
                            <BarChart data={barData}>
                                <CartesianGrid strokeDasharray="3 3" />
                                <XAxis dataKey="name" />
                                <YAxis />
                                <Tooltip />
                                <Legend />
                                <Bar dataKey="Before" fill="#FFD4A3" />
                                <Bar dataKey="After" fill="#20B2AA" />
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                </DialogContent>
            </Dialog>
        </>
    );
}
