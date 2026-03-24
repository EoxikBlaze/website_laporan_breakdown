import * as React from "react";
import { format } from "date-fns";
import { id as idLocale } from "date-fns/locale";
import { Calendar } from "@/components/ui/calendar";
import { TimePicker } from "@/components/ui/time-picker";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Button } from "@/components/ui/button";
import { CalendarIcon, Clock } from "lucide-react";
import { cn } from "@/lib/utils";

interface IosDateTimePickerProps {
    name: string;
    initialValue?: string;
    label?: string;
}

import { AnimatePresence, motion } from "framer-motion";

export function IosDateTimePicker({ name, initialValue, label, id: idProp }: IosDateTimePickerProps & { id?: string }) {
    const [date, setDate] = React.useState<Date | undefined>(() => {
        if (initialValue) {
            const parsed = new Date(initialValue);
            if (!isNaN(parsed.getTime())) return parsed;
        }
        return undefined;
    });
    const [isOpen, setIsOpen] = React.useState(false);
    const [isMobile, setIsMobile] = React.useState(false);

    // Use name as fallback for id if idProp is not provided
    const id = idProp || name;

    React.useEffect(() => {
        const checkMobile = () => setIsMobile(window.innerWidth < 640);
        checkMobile();
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);

    const formattedValue = date ? format(date, "yyyy-MM-dd HH:mm") : "";
    const displayValue = date ? format(date, "dd/MM/yyyy, HH:mm") : "Pilih waktu...";

    const handleTimeChange = (newDate: Date) => {
        setDate(newDate);
    };

    React.useEffect(() => {
        // Dispatch native change event so jQuery listeners can react
        const input = document.querySelector(`input[name="${name}"]`);
        if (input) {
            // Need setTimeout to ensure React updates the DOM value first
            setTimeout(() => {
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }, 50);
        }
    }, [date, name]);

    const PickerContent = ({ mobile = false }: { mobile?: boolean }) => (
        <div className={cn(
            "flex flex-col overflow-hidden",
            mobile ? "bg-white w-full max-h-[85vh]" : "w-auto"
        )}>
            {/* Header: Fixed at top */}
            <div className="px-6 py-6 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white shadow-xl flex-shrink-0">
                <div className="flex items-center justify-between">
                    <div className="flex flex-col">
                        <p className="text-[10px] uppercase tracking-[0.25em] font-black opacity-60 mb-1">Setel Laporan</p>
                        <span className="text-base font-bold">
                            {date ? format(date, "dd MMMM yyyy", { locale: idLocale }) : "Pilih Tanggal"}
                        </span>
                    </div>
                    <div className="px-5 py-2.5 bg-white/10 rounded-2xl text-xl font-black backdrop-blur-2xl border border-white/20 shadow-inner">
                        {date ? format(date, "HH:mm") : "--:--"}
                    </div>
                </div>
            </div>

            {/* Scrollable Body */}
            <div className={cn(
                "flex-1 overflow-y-auto",
                !mobile && "sm:overflow-visible"
            )}>
                <div className={cn(
                    "flex flex-col divide-y divide-neutral-100",
                    !mobile && "sm:flex-row sm:divide-y-0 sm:divide-x"
                )}>
                    {/* Calendar Section */}
                    <div className="p-4 sm:p-6 bg-white flex justify-center flex-shrink-0">
                        <Calendar
                            mode="single"
                            selected={date}
                            onSelect={(d) => d && setDate(prev => {
                                if (!prev) return d;
                                const newDate = new Date(d);
                                newDate.setHours(prev.getHours());
                                newDate.setMinutes(prev.getMinutes());
                                return newDate;
                            })}
                            locale={idLocale}
                            className="p-0"
                        />
                    </div>

                    {/* Time & Action Section */}
                    <div className={cn(
                        "px-8 py-8 bg-neutral-50/30 flex flex-col justify-between",
                        !mobile && "sm:w-[280px]"
                    )}>
                        <div className="space-y-8">
                            <div className="flex items-center gap-3">
                                <div className="p-2.5 bg-blue-100 rounded-2xl text-blue-600 shadow-sm border border-blue-200/50">
                                    <Clock size={18} />
                                </div>
                                <span className="text-[12px] font-black text-neutral-400 uppercase tracking-widest">Waktu Presisi</span>
                            </div>
                            
                            <div className="flex justify-center sm:justify-start scale-125 sm:scale-100 origin-center sm:origin-left py-6 sm:py-2">
                                <TimePicker 
                                    value={date} 
                                    onChange={handleTimeChange} 
                                />
                            </div>
                        </div>
                        
                        <Button 
                            type="button"
                            className="w-full mt-12 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-2xl h-14 shadow-2xl shadow-blue-600/30 font-black transition-all active:scale-[0.95] text-sm tracking-widest gap-3 group"
                            onClick={() => setIsOpen(false)}
                        >
                            <span>KONFIRMASI</span>
                            <svg className="w-5 h-5 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );

    return (
        <div className="w-full flex flex-col gap-2">
            <input type="hidden" name={name} value={formattedValue} />
            
            <Popover open={!isMobile && isOpen} onOpenChange={setIsOpen}>
                <PopoverTrigger asChild>
                    <Button
                        id={id}
                        variant={"outline"}
                        type="button"
                        onClick={() => isMobile && setIsOpen(true)}
                        className={cn(
                            "w-full justify-start text-left font-medium h-12 px-4 border-neutral-200 rounded-xl bg-white hover:bg-neutral-50 hover:border-blue-300 transition-all duration-200 shadow-sm",
                            !date && "text-neutral-400"
                        )}
                    >
                        <CalendarIcon className="mr-3 h-4 w-4 text-blue-500" />
                        <span className="flex-1 text-sm">{displayValue}</span>
                    </Button>
                </PopoverTrigger>
                {!isMobile && (
                    <PopoverContent 
                        className="w-auto p-0 border border-neutral-200 shadow-2xl rounded-[32px] overflow-hidden bg-white mt-2" 
                        align="center"
                        sideOffset={12}
                    >
                        <PickerContent />
                    </PopoverContent>
                )}
            </Popover>

            {/* Clear Button */}
            {date && (
                <button
                    type="button"
                    onClick={() => setDate(undefined)}
                    className="w-full py-2.5 px-4 rounded-xl border border-rose-200 text-rose-600 bg-rose-50 hover:bg-rose-100 text-xs font-bold transition-all shadow-sm active:scale-[0.98] flex items-center justify-center gap-2"
                >
                    <svg className="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Clear Selesai (Set ke OPEN)
                </button>
            )}

            <AnimatePresence>
                {isMobile && isOpen && (
                    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4">
                        <motion.div 
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            onClick={() => setIsOpen(false)}
                            className="absolute inset-0 bg-black/40 backdrop-blur-sm"
                        />
                        <motion.div
                            initial={{ opacity: 0, scale: 0.95, y: 20 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.95, y: 20 }}
                            className="relative z-10 w-full max-w-sm bg-white rounded-[32px] overflow-hidden shadow-2xl"
                        >
                            <PickerContent mobile />
                        </motion.div>
                    </div>
                )}
            </AnimatePresence>
        </div>
    );
}
