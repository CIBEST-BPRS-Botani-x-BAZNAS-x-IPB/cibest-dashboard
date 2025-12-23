import AppLogo from '@/components/app-logo';
import { Head } from '@inertiajs/react';
import { useState } from 'react';

interface Province {
    no: number;
    name: string;
    Q1: number;
    Q2: number;
    Q3: number;
    Q4: number;
    total: number;
}

interface CibestIndex {
    no: number;
    standard: string;
    index: number;
}

interface PovertyStandard {
    no: number;
    standard: string;
    monthlyIncome: number;
    yearlyIncome: number;
}

interface PovertyIndicator {
    indicator: string;
    before: number;
    after: number;
    change: number;
}

interface Props {
    description: string;
    provinces: Province[];
    cibestIndexes: CibestIndex[];
    povertyStandards: PovertyStandard[];
    povertyIndicators: PovertyIndicator[];
}

export default function CibestInfo({
    description,
    provinces,
    cibestIndexes,
    povertyStandards,
    povertyIndicators,
}: Props) {
    const [showAllProvinces, setShowAllProvinces] = useState(false);
    const displayedProvinces = showAllProvinces ? provinces : provinces.slice(0, 6);

    const formatCurrency = (value: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(value);
    };

    return (
        <>
            <Head title="Informasi CIBEST" />
            
            <div style={{ maxWidth: '1200px', margin: '0 auto', padding: '24px' }}>
                {/* Header */}
                <div style={{ marginBottom: '32px' }} className='flex items-center gap-6'>
                    <div className="w-65 h-full">
                        <AppLogo />
                    </div>
                    <div>
                        <h1 style={{ 
                            fontSize: '32px', 
                            fontWeight: '700', 
                            color: '#1e293b',
                            marginBottom: '16px'
                        }}>
                            CIBEST
                        </h1>
                        <p style={{ 
                            fontSize: '16px', 
                            color: '#64748b', 
                            lineHeight: '1.6'
                        }}>
                            {description}
                        </p>
                    </div>
                </div>

                {/* Tabel Sebaran Responden */}
                <div style={{
                    backgroundColor: 'white',
                    borderRadius: '8px',
                    boxShadow: '0 1px 3px 0 rgb(0 0 0 / 0.1)',
                    overflow: 'hidden',
                    marginBottom: '24px'
                }}>
                    <div style={{
                        backgroundColor: 'rgb(248, 250, 252)',
                        padding: '20px 24px',
                        borderBottom: '1px solid rgb(226, 232, 240)',
                    }}>
                        <h2 style={{
                            margin: 0,
                            fontSize: '18px',
                            fontWeight: '600',
                            color: '#1e293b',
                        }}>
                            Tabel Sebaran Responden
                        </h2>
                        <p style={{ margin: '4px 0 0 0', fontSize: '14px', color: '#64748b' }}>
                            Miskin Ekstrem
                        </p>
                    </div>
                    <div style={{ overflowX: 'auto' }}>
                        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                            <thead>
                                <tr style={{ backgroundColor: '#f8fafc' }}>
                                    <th style={tableHeaderStyle}>No.</th>
                                    <th style={tableHeaderStyle}>Provinsi</th>
                                    <th style={tableHeaderStyle}>Q1</th>
                                    <th style={tableHeaderStyle}>Q2</th>
                                    <th style={tableHeaderStyle}>Q3</th>
                                    <th style={tableHeaderStyle}>Q4</th>
                                    <th style={tableHeaderStyle}>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                {displayedProvinces.map((province) => (
                                    <tr key={province.no} style={{ borderBottom: '1px solid #e2e8f0' }}>
                                        <td style={tableCellStyle}>{province.no}</td>
                                        <td style={tableCellStyle}>{province.name}</td>
                                        <td style={tableCellStyle}>{province.Q1}</td>
                                        <td style={tableCellStyle}>{province.Q2}</td>
                                        <td style={tableCellStyle}>{province.Q3}</td>
                                        <td style={tableCellStyle}>{province.Q4}</td>
                                        <td style={tableCellStyle}>{province.total}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    {provinces.length > 6 && (
                        <div style={{ padding: '16px 24px', textAlign: 'center' }}>
                            <button
                                onClick={() => setShowAllProvinces(!showAllProvinces)}
                                style={{
                                    padding: '8px 16px',
                                    fontSize: '14px',
                                    fontWeight: '500',
                                    border: 'none',
                                    borderRadius: '6px',
                                    cursor: 'pointer',
                                    transition: 'all 0.2s',
                                    boxShadow: '0 1px 2px 0 rgb(0 0 0 / 0.05)',
                                    backgroundColor: '#14b8a6',
                                    color: 'white',
                                }}
                                onMouseEnter={(e) => {
                                    e.currentTarget.style.backgroundColor = '#0d9488';
                                }}
                                onMouseLeave={(e) => {
                                    e.currentTarget.style.backgroundColor = '#14b8a6';
                                }}
                            >
                                {showAllProvinces ? 'Show Less' : 'View More'}
                            </button>
                        </div>
                    )}
                </div>

                {/* Hasil Perhitungan Model CIBEST */}
                <div style={{
                    backgroundColor: 'white',
                    borderRadius: '8px',
                    boxShadow: '0 1px 3px 0 rgb(0 0 0 / 0.1)',
                    overflow: 'hidden',
                    marginBottom: '24px'
                }}>
                    <div style={{
                        backgroundColor: 'rgb(248, 250, 252)',
                        padding: '20px 24px',
                        borderBottom: '1px solid rgb(226, 232, 240)',
                    }}>
                        <h2 style={{
                            margin: 0,
                            fontSize: '18px',
                            fontWeight: '600',
                            color: '#1e293b',
                        }}>
                            Hasil Perhitungan Model CIBEST
                        </h2>
                    </div>
                    <div style={{ overflowX: 'auto' }}>
                        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                            <thead>
                                <tr style={{ backgroundColor: '#f8fafc' }}>
                                    <th style={tableHeaderStyle}>No.</th>
                                    <th style={tableHeaderStyle}>Standar Kemiskinan</th>
                                    <th style={tableHeaderStyle}>Index Kesejahteraan CIBEST</th>
                                </tr>
                            </thead>
                            <tbody>
                                {cibestIndexes.map((item) => (
                                    <tr key={item.no} style={{ borderBottom: '1px solid #e2e8f0' }}>
                                        <td style={tableCellStyle}>{item.no}</td>
                                        <td style={tableCellStyle}>{item.standard}</td>
                                        <td style={tableCellStyle}>{item.index.toFixed(2)}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Standar Kemiskinan */}
                <div style={{
                    backgroundColor: 'white',
                    borderRadius: '8px',
                    boxShadow: '0 1px 3px 0 rgb(0 0 0 / 0.1)',
                    overflow: 'hidden',
                    marginBottom: '24px'
                }}>
                    <div style={{
                        backgroundColor: 'rgb(248, 250, 252)',
                        padding: '20px 24px',
                        borderBottom: '1px solid rgb(226, 232, 240)',
                    }}>
                        <h2 style={{
                            margin: 0,
                            fontSize: '18px',
                            fontWeight: '600',
                            color: '#1e293b',
                        }}>
                            Standar Kemiskinan
                        </h2>
                    </div>
                    <div style={{ overflowX: 'auto' }}>
                        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                            <thead>
                                <tr style={{ backgroundColor: '#f8fafc' }}>
                                    <th style={tableHeaderStyle}>No.</th>
                                    <th style={tableHeaderStyle}>Standar Kemiskinan</th>
                                    <th style={tableHeaderStyle}>Pendapatan Keluarga Per Bulan</th>
                                    <th style={tableHeaderStyle}>Pendapatan Keluarga Per Tahun</th>
                                </tr>
                            </thead>
                            <tbody>
                                {povertyStandards.map((item) => (
                                    <tr key={item.no} style={{ borderBottom: '1px solid #e2e8f0' }}>
                                        <td style={tableCellStyle}>{item.no}</td>
                                        <td style={tableCellStyle}>{item.standard}</td>
                                        <td style={tableCellStyle}>{formatCurrency(item.monthlyIncome)}</td>
                                        <td style={tableCellStyle}>{formatCurrency(item.yearlyIncome)}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Indikator Kemiskinan Umum */}
                <div style={{
                    backgroundColor: 'white',
                    borderRadius: '8px',
                    boxShadow: '0 1px 3px 0 rgb(0 0 0 / 0.1)',
                    overflow: 'hidden',
                    marginBottom: '24px'
                }}>
                    <div style={{
                        backgroundColor: 'rgb(248, 250, 252)',
                        padding: '20px 24px',
                        borderBottom: '1px solid rgb(226, 232, 240)',
                    }}>
                        <h2 style={{
                            margin: 0,
                            fontSize: '18px',
                            fontWeight: '600',
                            color: '#1e293b',
                        }}>
                            Indikator Kemiskinan Umum
                        </h2>
                    </div>
                    <div style={{ overflowX: 'auto' }}>
                        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                            <thead>
                                <tr style={{ backgroundColor: '#f8fafc' }}>
                                    <th style={tableHeaderStyle}>Indikator</th>
                                    <th style={tableHeaderStyle}>Before</th>
                                    <th style={tableHeaderStyle}>After</th>
                                    <th style={tableHeaderStyle}>Perubahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {povertyIndicators.map((item, index) => (
                                    <tr key={index} style={{ borderBottom: '1px solid #e2e8f0' }}>
                                        <td style={tableCellStyle}>{item.indicator}</td>
                                        <td style={tableCellStyle}>{item.before.toFixed(2)}</td>
                                        <td style={tableCellStyle}>{item.after.toFixed(2)}</td>
                                        <td style={{
                                            ...tableCellStyle,
                                            color: item.change < 0 ? '#22c55e' : item.change > 0 ? '#ef4444' : '#64748b'
                                        }}>
                                            {item.change > 0 ? '+' : ''}{item.change.toFixed(2)}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </>
    );
}

const tableHeaderStyle: React.CSSProperties = {
    padding: '12px 16px',
    textAlign: 'left',
    fontSize: '14px',
    fontWeight: '600',
    color: '#475569',
    borderBottom: '1px solid #e2e8f0',
};

const tableCellStyle: React.CSSProperties = {
    padding: '12px 16px',
    fontSize: '14px',
    color: '#1e293b',
};
