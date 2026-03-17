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
                    className="w-[92vw] sm:w-auto p-0 border border-neutral-200 shadow-2xl rounded-[24px] overflow-hidden bg-white mt-2" 
                    align="start"
                    sideOffset={5}
                >
                    <div className="flex flex-col">
                        {/* Selected Time Display - Stays horizontal at top */}
                        <div className="px-6 py-4 bg-gradient-to-br from-blue-700 to-blue-900 text-white">
                            <p className="text-[10px] uppercase tracking-[0.2em] font-black opacity-70 mb-1">Konfigurasi Waktu</p>
                            <div className="flex items-center justify-between gap-8">
                                <span className="text-base font-bold truncate">
                                    {date ? format(date, "dd MMMM yyyy", { locale: id }) : "Pilih Tanggal"}
                                </span>
                                <div className="px-3 py-1.5 bg-white/20 rounded-xl text-sm font-black backdrop-blur-md border border-white/10 shrink-0">
                                    {date ? format(date, "HH:mm") : "--:--"}
                                </div>
                            </div>
                        </div>

                        {/* Interactive Area - Side-by-side on sm+ */}
                        <div className="flex flex-col sm:flex-row divide-y sm:divide-y-0 sm:divide-x divide-neutral-100">
                            {/* Left: Calendar */}
                            <div className="p-2 sm:p-4">
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
                                    className="p-1"
                                />
                            </div>

                            {/* Right: Clock & Action */}
                            <div className="px-6 py-6 sm:w-[220px] bg-neutral-50/50 flex flex-col justify-between">
                                <div>
                                    <div className="flex items-center gap-2 mb-4">
                                        <div className="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                                            <Clock size={14} />
                                        </div>
                                        <span className="text-[11px] font-bold text-neutral-500 uppercase tracking-wider">Setel Jam</span>
                                    </div>
                                    
                                    <div className="scale-90 origin-top-left">
                                        <TimePicker 
                                            value={date} 
                                            onChange={handleTimeChange} 
                                        />
                                    </div>
                                </div>
                                
                                <Button 
                                    className="w-full mt-8 bg-blue-600 hover:bg-blue-700 text-white rounded-xl h-11 shadow-lg shadow-blue-500/20 font-bold transition-all active:scale-[0.98] text-sm"
                                    onClick={() => setIsOpen(false)}
                                >
                                    Konfirmasi
                                </Button>
                            </div>
                        </div>
                    </div>
                </PopoverContent>
            </Popover>
        </div>
    );
}
