import * as React from "react";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import { Calendar } from "@/Components/ui/calendar";
import { TimePicker } from "@/Components/ui/time-picker";
import { Popover, PopoverContent, PopoverTrigger } from "@/Components/ui/popover";
import { Button } from "@/Components/ui/button";
import { CalendarIcon, Clock } from "lucide-react";
import { cn } from "@/lib/utils";

interface IosDateTimePickerProps {
    name: string;
    initialValue?: string;
    label?: string;
}

export function IosDateTimePicker({ name, initialValue, label }: IosDateTimePickerProps) {
    const [date, setDate] = React.useState<Date | undefined>(
        initialValue ? new Date(initialValue) : new Date()
    );
    const [isOpen, setIsOpen] = React.useState(false);

    const formattedValue = date ? format(date, "yyyy-MM-dd HH:mm") : "";
    const displayValue = date ? format(date, "dd/MM/yyyy, HH:mm") : "Pilih waktu...";

    const handleTimeChange = (newDate: Date) => {
        setDate(newDate);
    };

    return (
        <div className="w-full">
            <input type="hidden" name={name} value={formattedValue} />
            
            <Popover open={isOpen} onOpenChange={setIsOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant={"outline"}
                        className={cn(
                            "w-full justify-start text-left font-medium h-12 px-4 border-neutral-200 rounded-xl bg-white hover:bg-neutral-50 hover:border-blue-300 transition-all duration-200 shadow-sm",
                            !date && "text-neutral-400"
                        )}
                    >
                        <CalendarIcon className="mr-3 h-4 w-4 text-blue-500" />
                        <span className="flex-1 text-sm">{displayValue}</span>
                    </Button>
                </PopoverTrigger>
                <PopoverContent 
                    className="w-[92vw] sm:w-auto p-0 border border-neutral-200 shadow-2xl rounded-[28px] overflow-hidden bg-white mt-2" 
                    align="start"
                    sideOffset={8}
                >
                    <div className="flex flex-col">
                        {/* Header: Unified and Premium */}
                        <div className="px-6 py-5 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white shadow-md relative overflow-hidden">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                            <div className="relative z-10 flex items-center justify-between">
                                <div className="flex flex-col">
                                    <p className="text-[10px] uppercase tracking-[0.2em] font-black opacity-60 mb-1">Setel Waktu</p>
                                    <span className="text-base font-bold">
                                        {date ? format(date, "dd MMMM yyyy", { locale: id }) : "Pilih Tanggal"}
                                    </span>
                                </div>
                                <div className="px-4 py-2 bg-white/10 rounded-2xl text-lg font-black backdrop-blur-xl border border-white/20 shadow-inner">
                                    {date ? format(date, "HH:mm") : "--:--"}
                                </div>
                            </div>
                        </div>

                        {/* Content Area */}
                        <div className="flex flex-col sm:flex-row divide-y sm:divide-y-0 sm:divide-x divide-neutral-100">
                            {/* Left: Calendar - Reduced padding on mobile */}
                            <div className="p-2 sm:p-5 bg-white">
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
                                    locale={id}
                                    className="p-1 sm:p-0"
                                />
                            </div>

                            {/* Right: Time & Action - Unified background on mobile */}
                            <div className="px-6 py-6 sm:w-[260px] bg-white sm:bg-neutral-50/30 flex flex-col justify-between border-t sm:border-t-0 border-neutral-50">
                                <div className="space-y-6">
                                    <div className="flex items-center gap-2.5">
                                        <div className="p-2 bg-blue-50 rounded-xl text-blue-600 shadow-sm border border-blue-100/50">
                                            <Clock size={16} />
                                        </div>
                                        <span className="text-[11px] font-black text-neutral-400 uppercase tracking-widest">Waktu Presisi</span>
                                    </div>
                                    
                                    <div className="flex justify-center sm:justify-start scale-110 sm:scale-100 origin-center sm:origin-left py-4 sm:py-2">
                                        <TimePicker 
                                            value={date} 
                                            onChange={handleTimeChange} 
                                        />
                                    </div>
                                </div>
                                
                                <Button 
                                    className="w-full mt-10 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-2xl h-12 shadow-xl shadow-blue-600/20 font-black transition-all active:scale-[0.96] text-sm tracking-wide gap-2 group"
                                    onClick={() => setIsOpen(false)}
                                >
                                    <span>Konfirmasi Waktu</span>
                                    <div className="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                    </div>
                                </Button>
                            </div>
                        </div>
                    </div>
                </PopoverContent>
            </Popover>
        </div>
    );
}
